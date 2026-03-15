<?php

namespace App\Livewire\Backend\Category;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.backend')]
class Form extends Component
{
    use WithFileUploads;

    public ?Category $category = null;
    public $isEditMode = false;

    public $name = '';
    public $description = '';
    
    public $image;
    public $existingImageUrl = null;

    public function mount(Category $category = null)
    {
        if ($category && $category->exists) {
            $this->category = $category;
            $this->isEditMode = true;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->existingImageUrl = $category->image_url;
        } else {
            $this->isEditMode = false;
        }
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:200|unique:categories,name' . ($this->isEditMode ? ',' . $this->category->id : ''),
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048', // 2MB Max
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'กรุณากรอกชื่อหมวดหมู่',
            'name.max' => 'ชื่อหมวดหมู่ต้องไม่เกิน 200 ตัวอักษร',
            'name.unique' => 'ชื่อหมวดหมู่นี้มีอยู่ในระบบแล้ว',
            'description.max' => 'รายละเอียดต้องไม่เกิน 1000 ตัวอักษร',
            'image.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพเท่านั้น',
            'image.max' => 'ขนาดรูปภาพต้องไม่เกิน 2MB',
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
        ];

        if ($this->image) {
            if ($this->isEditMode && $this->existingImageUrl) {
                Storage::delete($this->existingImageUrl);
            }
            $data['image_url'] = $this->image->store('categories', 'public');
        }

        if ($this->isEditMode) {
            $this->category->update($data);
            $message = 'อัปเดตข้อมูลหมวดหมู่เรียบร้อยแล้ว';
        } else {
            Category::create($data);
            $message = 'สร้างหมวดหมู่ใหม่เรียบร้อยแล้ว';
        }

        session()->flash('success', $message);
        return redirect()->route('backend.categories.index');
    }

    public function removeExistingImage()
    {
        if ($this->isEditMode && $this->existingImageUrl) {
            Storage::delete($this->existingImageUrl);
            $this->category->update(['image_url' => null]);
            $this->existingImageUrl = null;
        }
    }

    public function render()
    {
        return view('livewire.backend.category.form');
    }
}
