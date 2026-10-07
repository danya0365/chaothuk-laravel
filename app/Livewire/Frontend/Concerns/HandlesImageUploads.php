<?php

namespace App\Livewire\Frontend\Concerns;

use App\Services\UserImageService;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Shared upload handling for the frontend create/edit forms.
 *
 * The forms previously uploaded through POST /api/upload/image with fetch() and
 * read the returned URL back into a Livewire property. Now that the mobile API is
 * versioned separately, the frontend calls UserImageService directly and keeps the
 * same URL properties, so the blade views only swap fetch() for a file input.
 *
 * @property string|null $primaryImage
 * @property array $galleryImages
 */
trait HandlesImageUploads
{
    /** Persisted URLs, shared by every form that uses this trait. */
    public ?string $primaryImage = null;

    public array $galleryImages = [];

    /** Raw uploads waiting to be handled. */
    public $primaryImageFile = null;

    public $galleryImageFiles = [];

    /** Last upload error, in Thai for the user. */
    public ?string $uploadError = null;

    public function updatedPrimaryImageFile(): void
    {
        $this->uploadError = null;

        $url = $this->storeUpload($this->primaryImageFile);

        if ($url) {
            $this->primaryImage = $url;
            $this->primaryImageFile = null;
        }
    }

    public function updatedGalleryImageFiles(): void
    {
        $this->uploadError = null;

        foreach ($this->galleryImageFiles as $file) {
            $url = $this->storeUpload($file);

            if ($url) {
                $this->galleryImages[] = $url;
            }
        }

        $this->galleryImageFiles = [];
    }

    public function removePrimaryImage(): void
    {
        $this->primaryImage = null;
    }

    public function removeGalleryImage(int $index): void
    {
        if (! array_key_exists($index, $this->galleryImages)) {
            return;
        }

        array_splice($this->galleryImages, $index, 1);
        $this->galleryImages = array_values($this->galleryImages);
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

    /**
     * Pre-fill the gallery for edit forms without touching storage.
     *
     * @param  array<int, string>  $urls
     */
    public function setGalleryImages(array $urls): void
    {
        $this->galleryImages = array_values(array_filter($urls));
    }
}
