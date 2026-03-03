<?php

namespace App\Livewire\Frontend;

use App\Models\Province;
use App\Models\Recruit;
use App\Models\WorkType;
use Livewire\Component;

class RecruitEdit extends Component
{
    public int $id;
    public ?Recruit $recruit = null;

    public string $title = '';
    public string $description = '';
    public float $budget = 0;
    public ?int $provinceId = null;
    public ?int $workTypeId = null;
    public ?string $primaryImage = null;
    public array $galleryImages = [];
    public ?float $latitude = null;
    public ?float $longitude = null;
    public string $recruitStatus = '';

    public ?string $successMessage = null;

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->recruit = Recruit::findOrFail($id);

        if (!auth()->check() || auth()->id() !== $this->recruit->author_id) {
            abort(403);
        }

        $this->title = $this->recruit->title ?? '';
        $this->description = $this->recruit->description ?? '';
        $this->budget = $this->recruit->budget ?? 0;
        $this->provinceId = $this->recruit->province_id;
        $this->workTypeId = $this->recruit->work_type_id;
        $this->primaryImage = $this->recruit->primary_image;
        $this->latitude = $this->recruit->latitude;
        $this->longitude = $this->recruit->longitude;
        $this->recruitStatus = $this->recruit->recruit_status ?? '';

        // Load gallery images
        $raw = $this->recruit->images;
        if (is_array($raw)) {
            $this->galleryImages = $raw;
        } elseif (is_string($raw)) {
            $this->galleryImages = json_decode($raw, true) ?? [];
        }
    }

    public function removePrimaryImage(): void
    {
        $this->primaryImage = null;
    }

    public function removeGalleryImage(int $index): void
    {
        $images = $this->galleryImages;
        array_splice($images, $index, 1);
        $this->galleryImages = $images;
    }

    public function save(): void
    {
        $this->validate([
            'title'       => 'required|min:5|max:255',
            'description' => 'required|min:10',
            'budget'      => 'required|numeric|min:0',
            'provinceId'  => 'required',
            'workTypeId'  => 'required',
        ]);

        $this->recruit->update([
            'title'          => $this->title,
            'description'    => $this->description,
            'budget'         => $this->budget,
            'province_id'    => $this->provinceId,
            'work_type_id'   => $this->workTypeId,
            'primary_image'  => $this->primaryImage,
            'images'         => !empty($this->galleryImages) ? $this->galleryImages : null,
            'latitude'       => $this->latitude,
            'longitude'      => $this->longitude,
            'recruit_status' => $this->recruitStatus,
        ]);

        $this->successMessage = '✅ บันทึกเรียบร้อยแล้ว';
    }

    public function render()
    {
        return view('livewire.frontend.recruit-edit', [
            'provinces' => Province::orderBy('name_th')->get(),
            'workTypes' => WorkType::all(),
        ])->layout('frontend.layout', ['title' => 'แก้ไขประกาศ — Chaothuk']);
    }
}
