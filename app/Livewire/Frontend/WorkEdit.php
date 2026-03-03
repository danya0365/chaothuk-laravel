<?php

namespace App\Livewire\Frontend;

use App\Models\Work;
use App\Models\Province;
use App\Models\WorkType;
use App\Models\Category;
use Livewire\Component;

class WorkEdit extends Component
{
    public int $id;
    public ?Work $work = null;

    // Form fields
    public string $title = '';
    public string $code = '';
    public string $description = '';
    public float $price = 0;
    public ?int $provinceId = null;
    public ?int $workTypeId = null;
    public ?string $primaryImage = null;
    public array $galleryImages = [];
    public ?float $latitude = null;
    public ?float $longitude = null;
    public string $workStatus = '';
    public array $selectedCategories = [];

    public ?string $successMessage = null;

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->work = Work::with('categories')->findOrFail($id);

        // Only owner can edit
        if (!auth()->check() || auth()->id() !== $this->work->author_id) {
            abort(403);
        }

        $this->title = $this->work->title ?? '';
        $this->code = $this->work->code ?? '';
        $this->description = $this->work->description ?? '';
        $this->price = $this->work->price ?? 0;
        $this->provinceId = $this->work->province_id;
        $this->workTypeId = $this->work->work_type_id;
        $this->primaryImage = $this->work->primary_image;
        $this->latitude = $this->work->latitude;
        $this->longitude = $this->work->longitude;
        $this->workStatus = $this->work->work_status ?? '';
        $this->selectedCategories = $this->work->categories->pluck('id')->toArray();

        // Load gallery images
        $raw = $this->work->images;
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
            'code'        => 'required|max:50|unique:works,code,' . $this->work->id,
            'description' => 'required|min:10',
            'price'       => 'required|numeric|min:0',
            'provinceId'  => 'required',
            'workTypeId'  => 'required',
        ]);

        $this->work->update([
            'title'        => $this->title,
            'code'         => $this->code,
            'description'  => $this->description,
            'price'        => $this->price,
            'province_id'  => $this->provinceId,
            'work_type_id' => $this->workTypeId,
            'primary_image'=> $this->primaryImage,
            'images'       => !empty($this->galleryImages) ? $this->galleryImages : null,
            'latitude'     => $this->latitude,
            'longitude'    => $this->longitude,
            'work_status'  => $this->workStatus,
        ]);

        $this->work->categories()->sync($this->selectedCategories);
        $this->successMessage = '✅ บันทึกเรียบร้อยแล้ว';
    }

    public function render()
    {
        return view('livewire.frontend.work-edit', [
            'provinces'  => Province::orderBy('name_th')->get(),
            'workTypes'  => WorkType::all(),
            'categories' => Category::all(),
        ])->layout('frontend.layout', ['title' => 'แก้ไขงาน — Chaothuk']);
    }
}
