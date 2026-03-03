<?php

namespace App\Livewire\Frontend;

use App\Models\Province;
use App\Models\Recruit;
use App\Models\WorkType;
use Livewire\Component;

class RecruitCreate extends Component
{
    public string $title = '';
    public string $description = '';
    public ?float $budget = null;
    public string $provinceId = '';
    public string $workTypeId = '';
    public ?float $latitude = null;
    public ?float $longitude = null;

    // Image URLs (set via JS after upload to /api/upload/image)
    public ?string $primaryImage = null;
    public array $galleryImages = [];

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

    public function submit(): void
    {
        $this->validate([
            'title'       => 'required|min:5|max:255',
            'description' => 'required|min:10',
            'budget'      => 'required|numeric|min:0',
            'provinceId'  => 'required|exists:provinces,id',
            'workTypeId'  => 'required|exists:work_types,id',
        ]);

        $recruit = Recruit::create([
            'title'           => $this->title,
            'description'     => $this->description,
            'budget'          => $this->budget,
            'province_id'     => $this->provinceId,
            'work_type_id'    => $this->workTypeId,
            'author_id'       => auth()->id(),
            'primary_image'   => $this->primaryImage,
            'images'          => !empty($this->galleryImages) ? $this->galleryImages : null,
            'latitude'        => $this->latitude,
            'longitude'       => $this->longitude,
            'recruit_status'  => 'stand-by',
        ]);

        $this->redirect(route('frontend.recruits.show', $recruit->id));
    }

    public function render()
    {
        $provinces = Province::orderBy('name_th')->get();
        $workTypes = WorkType::orderBy('title')->get();

        return view('livewire.frontend.recruit-create', compact('provinces', 'workTypes'))
            ->layout('frontend.layout', ['title' => 'สร้างประกาศหางาน — Chaothuk']);
    }
}
