<?php

namespace App\Livewire\Frontend;

use App\Livewire\Frontend\Concerns\HandlesImageUploads;
use App\Models\Category;
use App\Models\Province;
use App\Models\Work;
use App\Models\WorkType;
use Livewire\Component;
use Livewire\WithFileUploads;

class WorkCreate extends Component
{
    use HandlesImageUploads, WithFileUploads;

    public string $title = '';

    public string $code = '';

    public string $description = '';

    public ?float $price = null;

    public string $provinceId = '';

    public string $workTypeId = '';

    public ?float $latitude = null;

    public ?float $longitude = null;

    public array $selectedCategories = [];

    public bool $isBlocked = false;

    public function mount()
    {
        if (! auth()->check() || ! auth()->user()->isPermission(\App\Enums\Permission::CREATE_WORK->value)) {
            $this->isBlocked = true;
        }
    }

    public function submit(): void
    {
        if ($this->isBlocked) {
            return;
        }

        $this->validate([
            'title' => 'required|min:5|max:255',
            'code' => 'required|max:50|unique:works,code',
            'description' => 'required|min:10',
            'price' => 'required|numeric|min:0',
            'provinceId' => 'required|exists:provinces,id',
            'workTypeId' => 'required|exists:work_types,id',
        ]);

        $work = Work::create([
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'province_id' => $this->provinceId,
            'work_type_id' => $this->workTypeId,
            'author_id' => auth()->id(),
            'primary_image' => $this->primaryImage,
            'images' => ! empty($this->galleryImages) ? $this->galleryImages : null,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'work_status' => 'stand-by',
        ]);

        if (! empty($this->selectedCategories)) {
            $work->categories()->sync($this->selectedCategories);
        }

        $this->redirect(route('frontend.works.show', $work->id));
    }

    public function render()
    {
        $provinces = Province::orderBy('name_th')->get();
        $workTypes = WorkType::orderBy('title')->get();
        $categories = Category::orderBy('name')->get();

        return view('livewire.frontend.work-create', compact('provinces', 'workTypes', 'categories'))
            ->layout('frontend.layout', ['title' => 'สร้างงานใหม่ — Chaothuk']);
    }
}
