<?php

namespace App\Livewire\Frontend\Concerns;

use App\Services\UserImageService;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Upload handling for the portfolio forms.
 *
 * Portfolios have a flat image list (`images`), no primary/gallery split, so they
 * get their own trait rather than forcing HandlesImageUploads onto the shape.
 *
 * @property array $images
 */
trait HandlesPortfolioUploads
{
    public array $images = [];

    /** Raw uploads waiting to be handled. */
    public $imageFiles = [];

    /** Last upload error, in Thai for the user. */
    public ?string $uploadError = null;

    public function updatedImageFiles(): void
    {
        $this->uploadError = null;

        foreach ($this->imageFiles as $file) {
            $url = $this->storeUpload($file);

            if ($url) {
                $this->images[] = $url;
            }
        }

        $this->imageFiles = [];
    }

    public function removeImage(int $index): void
    {
        if (! array_key_exists($index, $this->images)) {
            return;
        }

        array_splice($this->images, $index, 1);
        $this->images = array_values($this->images);
    }

    /**
     * Move one validated upload into the public disk and return its URL.
     */
    protected function storeUpload(mixed $file): ?string
    {
        if (! $file instanceof TemporaryUploadedFile) {
            return null;
        }

        $result = app(UserImageService::class)->handleImageUpload($file);

        if (! ($result['status'] ?? false)) {
            $this->uploadError = $result['message'] ?? 'อัพโหลดรูปไม่สำเร็จ';

            return null;
        }

        return $result['data']['resize'] ?? $result['data']['original'] ?? null;
    }
}
