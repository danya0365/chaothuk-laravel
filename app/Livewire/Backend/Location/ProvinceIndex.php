<?php

namespace App\Livewire\Backend\Location;

use App\Models\Province;
use App\Models\Geography;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ProvinceIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $geographyFilter = '';

    // Form state
    public $isModalOpen = false;
    public $isEditMode = false;
    public $provinceId;
    public $code;
    public $name_th;
    public $name_en;
    public $geography_id;
    public $image;

    protected $rules = [
        'code' => 'required|string|max:255',
        'name_th' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
        'geography_id' => 'required|exists:geographies,id',
        'image' => 'nullable|url', // Using URL for images for now or switch to actual upload later
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedGeographyFilter()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $province = Province::findOrFail($id);
        $this->provinceId = $province->id;
        $this->code = $province->code;
        $this->name_th = $province->name_th;
        $this->name_en = $province->name_en;
        $this->geography_id = $province->geography_id;
        $this->image = $province->image;

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'code' => $this->code,
            'name_th' => $this->name_th,
            'name_en' => $this->name_en,
            'geography_id' => $this->geography_id,
            'image' => $this->image,
        ];

        if ($this->isEditMode) {
            Province::findOrFail($this->provinceId)->update($data);
            session()->flash('success', 'อัพเดทจังหวัดสำเร็จ');
        } else {
            Province::create($data);
            session()->flash('success', 'เพิ่มจังหวัดสำเร็จ');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        Province::findOrFail($id)->delete();
        session()->flash('success', 'ลบจังหวัดสำเร็จ');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['provinceId', 'code', 'name_th', 'name_en', 'geography_id', 'image']);
        $this->resetValidation();
    }

    public function render()
    {
        $query = Province::query()->with('geography');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name_th', 'like', '%' . $this->search . '%')
                  ->orWhere('name_en', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->geographyFilter) {
            $query->where('geography_id', $this->geographyFilter);
        }

        $provinces = $query->orderBy('name_th', 'asc')->paginate(20);
        $geographies = Geography::orderBy('name', 'asc')->get();

        return view('livewire.backend.location.province-index', compact('provinces', 'geographies'))
            ->layout('layouts.backend', ['title' => 'จัดการที่ตั้ง (Locations)']);
    }
}
