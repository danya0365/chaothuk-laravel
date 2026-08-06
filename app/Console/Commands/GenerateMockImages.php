<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Generate photorealistic mock images locally via ComfyUI (flux-schnell Q4 GGUF).
 *
 * Serves the mock-image work: instead of random picsum.photos URLs, the seeder
 * shows real-looking Thai hauling photos generated on this machine.
 *
 * Requires the local ComfyUI server (see easy-video-factory SETUP.md):
 *   conda activate comfyui && python ~/ComfyUI/main.py --port 8188
 *
 * usage:
 *   php artisan mock-images:generate                    # all buckets
 *   php artisan mock-images:generate --bucket=work      # one bucket
 *   php artisan mock-images:generate --force            # regen even if exist
 */
class GenerateMockImages extends Command
{
    protected $signature = 'mock-images:generate
        {--bucket= : Only this bucket (work|recruit|portfolio|banner)}
        {--force : Regenerate files that already exist}';

    protected $description = 'Generate photorealistic mock images via local ComfyUI (flux-schnell)';

    private const MAX_RETRIES = 5;

    public function handle(): int
    {
        $catalog = config('mock-images');
        $baseUrl = rtrim($catalog['comfyui_url'], '/');

        // ── 1. health check ────────────────────────────────────────────────
        try {
            $stats = Http::timeout(5)->get($baseUrl . '/system_stats')->json();
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            $this->error('ต่อ ComfyUI ไม่ได้ — สตาร์ทก่อนด้วย:');
            $this->error('  conda activate comfy && python ~/ComfyUI/main.py --port 8188');
            return self::FAILURE;
        }
        if (!isset($stats['system'])) {
            $this->error('ComfyUI /system_stats ไม่ได้ตอบตามคาด');
            return self::FAILURE;
        }
        $this->line('ComfyUI: online — ' . json_encode($stats['system']['comfyui_version'] ?? '?'));

        // ── 2. pick buckets ──────────────────────────────────────────────
        $bucketFilter = $this->option('bucket');
        $buckets = $catalog['buckets'];
        if ($bucketFilter) {
            if (! isset($buckets[$bucketFilter])) {
                $this->error("ไม่รู้จัก bucket: {$bucketFilter}");
                return self::FAILURE;
            }
            $buckets = [$bucketFilter => $buckets[$bucketFilter]];
        }

        $force = (bool) $this->option('force');
        $outRoot = $catalog['output_dir'];

        foreach ($buckets as $bucketName => $specs) {
            $this->newLine();
            $this->info('▶ bucket: ' . $bucketName . ' (' . count($specs) . ' images)');

            foreach ($specs as $i => $spec) {
                $slug = $spec['slug'];
                $size = $catalog['sizes'][$bucketName === 'banner' ? 'banner' : 'card'];

                $dir = "{$outRoot}/{$bucketName}";
                $finalPath = "{$dir}/{$slug}.webp";
                if (is_file($finalPath) && ! $force) {
                    $this->warn("  [{$i}] skips {$slug} (exists)");
                    continue;
                }

                $seed = $this->seedFor($bucketName, $slug);
                $this->line("  [{$i}] {$slug} — {$spec['title']} ({$size['width']}x{$size['height']}, seed {$seed})");

                $png = $this->generate($baseUrl, $spec['prompt'], $seed, $size, self::MAX_RETRIES);
                if (! $png) {
                    $this->error("  ✗ failed {$slug}");
                    return self::FAILURE;
                }

                // convert+save as webp via Intervention (imagick available)
                try {
                    $cropTo = $bucketName === 'banner' ? [1200, 400] : null;
                    \App\Services\MockImageService::saveWebp($png, $finalPath, $size['width'], $size['height'], $cropTo);
                } catch (\Throwable $e) {
                    $this->warn("  image conversion failed ({$e->getMessage()}) — saving raw png");
                    file_put_contents("{$dir}/{$slug}.png", $png);
                }
            }
        }

        $this->newLine(2);
        $this->info('Done. Run `php artisan db:seed --class=MockSeeder` to bind them.');

        return self::SUCCESS;
    }

    /**
     * Submit flux-gguf workflow to ComfyUI, poll history, download PNG bytes.
     *
     * @return resource|null
     */
    private function generate(string $baseUrl, string $prompt, int $seed, array $size, int $maxAttempts = self::MAX_RETRIES): ?string
    {
        $attempt = 0;
        while ($attempt < $maxAttempts) {
            $attempt++;
            try {
                $workflow = $this->fluxGraph($prompt, $seed, $size);
                $resp = Http::timeout(30)->post($baseUrl . '/prompt', ['prompt' => $workflow]);
                if (! $resp->ok()) {
                    $this->warn("  submit failed HTTP {$resp->status()}: " . $resp->body());
                    continue;
                }
                $promptId = $resp->json('prompt_id');
                if (! $promptId) {
                    $this->warn('  submit ok but no prompt_id');
                    continue;
                }

                $png = $this->waitForImage($baseUrl, $promptId);
                if ($png) {
                    return $png;
                }
            } catch (\Throwable $e) {
                $this->warn('  retry after error: ' . $e->getMessage());
                sleep(1);
            }
        }
        return null;
    }

    /**
     * Poll /history/{id} until outputs appear, then download the image.
     */
    private function waitForImage(string $baseUrl, string $promptId): ?string
    {
        $deadline = microtime(true) + 600; // 10 min
        $lastStatus = null;
        while (microtime(true) < $deadline) {
            $history = Http::timeout(5)->get("{$baseUrl}/history/{$promptId}")->json();
            $entry = $history[$promptId] ?? null;

            if ($entry && $entry['status']['completed'] ?? false) {
                foreach ($entry['outputs'] ?? [] as $out) {
                    foreach ($out['images'] ?? [] as $img) {
                        $png = $this->download($baseUrl, $img);
                        if ($png) {
                            return $png;
                        }
                    }
                }
                return null;
            }

            // status_update hint (executing progress)
            $newStatus = $entry['status']['status_str'] ?? null;
            if ($newStatus !== $lastStatus) {
                $this->line('    (' . ($newStatus ?? 'queued') . ')');
                $lastStatus = $newStatus;
            }
            usleep(250000);
        }
        return null;
    }

    /**
     * GET /view?filename=...&subfolder=...&type=... — returns PNG bytes.
     */
    private function download(string $baseUrl, array $img): ?string
    {
        $query = http_build_query([
            'filename'  => $img['filename'],
            'subfolder' => $img['subfolder'] ?? '',
            'type'      => $img['type'] ?? 'output',
        ]);
        $resp = Http::timeout(30)->get("{$baseUrl}/view?{$query}");
        if (! $resp->ok()) {
            $this->warn('  download failed HTTP ' . $resp->status());
            return null;
        }
        return $resp->body();
    }

    /**
     * Build the flux-schnell Q4 GGUF workflow graph (patched by node title).
     *
     * Same shape as the working EVF workflow (flux-gguf-txt2img.json):
     *  UnetLoaderGGUF + DualCLIPLoader(flux) + VAELoader + EmptySD3LatentImage
     *  + CLIPTextEncode -> FluxGuidance -> ConditioningZeroOut -> KSampler
     *  -> VAEDecode -> SaveImage
     */
    private function fluxGraph(string $prompt, int $seed, array $size): array
    {
        return [
            '3' => [
                'class_type' => 'KSampler',
                'inputs' => [
                    'seed' => $seed,
                    'steps' => 4,
                    'cfg' => 1,
                    'sampler_name' => 'euler',
                    'scheduler' => 'simple',
                    'denoise' => 1,
                    'model' => ['4', 0],
                    'positive' => ['10', 0],
                    'negative' => ['11', 0],
                    'latent_image' => ['5', 0],
                ],
            ],
            '4' => [
                'class_type' => 'UnetLoaderGGUF',
                'inputs' => ['unet_name' => 'flux1-schnell-Q4_K_S.gguf'],
            ],
            '12' => [
                'class_type' => 'DualCLIPLoader',
                'inputs' => [
                    'clip_name1' => 't5xxl_fp8_e4m3fn.safetensors',
                    'clip_name2' => 'clip_l.safetensors',
                    'type' => 'flux',
                ],
            ],
            '13' => [
                'class_type' => 'VAELoader',
                'inputs' => ['vae_name' => 'flux-ae.safetensors'],
            ],
            '5' => [
                'class_type' => 'EmptySD3LatentImage',
                'inputs' => [
                    'width' => $size['width'],
                    'height' => $size['height'],
                    'batch_size' => 1,
                ],
            ],
            '6' => [
                'class_type' => 'CLIPTextEncode',
                'inputs' => ['text' => $prompt, 'clip' => ['12', 0]],
            ],
            '10' => [
                'class_type' => 'FluxGuidance',
                'inputs' => ['guidance' => 3.5, 'conditioning' => ['6', 0]],
            ],
            '11' => [
                'class_type' => 'ConditioningZeroOut',
                'inputs' => ['conditioning' => ['6', 0]],
            ],
            '8' => [
                'class_type' => 'VAEDecode',
                'inputs' => ['samples' => ['3', 0], 'vae' => ['13', 0]],
            ],
            '9' => [
                'class_type' => 'SaveImage',
                'inputs' => ['filename_prefix' => 'evf', 'images' => ['8', 0]],
            ],
        ];
    }

    private function seedFor(string $bucket, string $slug): int
    {
        return crc32("{$bucket}/{$slug}");
    }
}