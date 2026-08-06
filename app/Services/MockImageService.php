<?php

namespace App\Services;

use Intervention\Image\ImageManager;

/**
 * Helpers for the locally-generated mock images (ComfyUI / flux-schnell).
 *
 * Generated PNGs live (gitignored) under database/images/mock/source.
 * On seed, they are copied into storage/app/public/mock/ so the DB can hold
 * "storage/mock/{bucket}/{slug}.webp" URLs served by the public/storage link.
 */
class MockImageService
{
    /**
     * Convert ComfyUI PNG bytes to a gitignored webp source file.
     *
     * `$width/$height` is the ComfyUI *generation* size. When the final asset
     * must be a different aspect (e.g. banner 1200x400), pass $cropTo so the
     * image is center-cropped to that box before webp encoding.
     */
    public static function saveWebp(
        string $pngBytes,
        string $absolutePath,
        int $width,
        int $height,
        ?array $cropTo = null,
    ): void {
        $manager = ImageManager::imagick();

        $image = $manager->read($pngBytes);

        if ($cropTo) {
            [$cropW, $cropH] = $cropTo;
            if (abs($image->width() / $image->height() - $cropW / $cropH) > 0.02) {
                $image->coverDown($cropW, $cropH);
            }
        }

        $dir = dirname($absolutePath);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $image->toWebp(82)->save($absolutePath);
    }

    /**
     * Mirror the gitignored source set into storage/app/public/mock.
     * No-op when nothing generated yet (fresh clone before mock-images:generate).
     */
    public static function copyToPublic(): void
    {
        $root = database_path('images/mock');
        if (! is_dir($root)) {
            return;
        }

        $base = config('mock-images.public_base', 'mock');
        $publicRoot = storage_path("app/public/{$base}");

        foreach (['work', 'recruit', 'portfolio', 'banner'] as $bucket) {
            $from = "{$root}/{$bucket}";
            if (! is_dir($from)) {
                continue;
            }
            foreach (glob("{$from}/*.webp") ?: [] as $file) {
                $name = basename($file);
                $destDir = "{$publicRoot}/{$bucket}";
                if (! is_dir($destDir)) {
                    mkdir($destDir, 0775, true);
                }
                $dest = "{$destDir}/{$name}";
                if (is_file($dest)) {
                    continue; // don't overwrite existing
                }
                copy($file, $dest);
            }
        }
    }

    /**
     * Path -> public URL (e.g. "mock/work/pickup-1.webp" -> "storage/mock/..."),
     * DB value only; no leading slash.
     */
    public static function publicRel(string $bucket, string $slug): string
    {
        return 'storage/' . config('mock-images.public_base', 'mock') . "/{$bucket}/{$slug}.webp";
    }

    /**
     * Assign deterministic local-mock images to a collection of Works/Recruits,
     * picking per-row from the pool matching the row's work_type title.
     * Images must already be copied into storage (see copyToPublic()).
     *
     * @param \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection $models
     */
    public static function assignToModels($models, string $bucket): void
    {
        $workTypeTitles = \App\Models\WorkType::pluck('title', 'id')->all();
        $counter = 0;

        $models->each(function ($model) use ($bucket, $workTypeTitles, &$counter) {
            $title = $workTypeTitles[$model->work_type_id] ?? null;
            $slugs = self::pool($bucket, $title);
            if (empty($slugs)) {
                $slugs = self::pool($bucket); // fallback: any slug
            }
            if (empty($slugs)) {
                return;
            }

            $i = $counter % count($slugs);
            $counter++;

            $primary = self::publicRel($bucket, $slugs[$i]);
            $more = [
                self::publicRel($bucket, $slugs[($i + 1) % count($slugs)]),
                self::publicRel($bucket, $slugs[($i + 2) % count($slugs)]),
            ];

            $model->forceFill([
                'primary_image' => $primary,
                'images'        => $more,
            ])->saveQuietly();
        });
    }

    /**
     * All slugs for a bucket, optionally narrowed to a work-type prefix
     * (e.g. work_type title "รถกะบะ" -> slug prefix "pickup").
     */
    public static function pool(string $bucket, ?string $workTypeTitle = null): array
    {
        $specs = collect(data_get(config('mock-images.buckets'), $bucket, []));
        if ($workTypeTitle !== null) {
            $prefix = data_get(config('mock-images.work_type_slugs'), $workTypeTitle);
            if ($prefix) {
                $specs = $specs->filter(fn ($s, $_k) => str_starts_with($s['slug'], $prefix . '-'));
            }
        }
        return $specs->pluck('slug')->values()->all();
    }
}