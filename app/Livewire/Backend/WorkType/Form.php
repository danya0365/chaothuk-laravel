<?php

namespace App\Livewire\Backend\WorkType;

use App\Models\WorkType;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.backend')]
class Form extends Component
{
    use WithFileUploads;

    public ?WorkType $workType = null;
    public $isEditMode = false;

    public $title = '';
    public $image;
    public $existingImageUrl = null;

    public function mount(?WorkType $workType = null)
    {
        if ($workType && $workType->exists) {
            $this->workType = $workType;
            $this->isEditMode = true;
            $this->title = $workType->title;
            // image in db might be 'work_types/filename.jpg' or null
            $this->existingImageUrl = $workType->image;
        }
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048', // 2MB Max
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'กรุณากรอกชื่อประเภทงาน',
            'title.max' => 'ชื่อประเภทงานต้องไม่เกิน 255 ตัวอักษร',
            'image.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพเท่านั้น',
            'image.max' => 'ขนาดรูปภาพต้องไม่เกิน 2MB',
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
        ];

        if ($this->image) {
            if ($this->isEditMode && $this->existingImageUrl) {
                $relativePath = str_replace(asset('storage') . '/', '', $this->existingImageUrl);
                Storage::disk('public')->delete($relativePath);
            }
            
            $imageService = app(\App\Services\UserImageService::class);
            $uploadResult = $imageService->handleWorkTypeUpload($this->image);
            
            if (isset($uploadResult['status']) && $uploadResult['status']) {
                $data['image'] = $uploadResult['url'];
            }
        }

        if ($this->isEditMode) {
            $this->workType->update($data);
            $message = 'อัปเดตข้อมูลประเภทงานเรียบร้อยแล้ว';
        } else {
            WorkType::create($data);
            $message = 'สร้างประเภทงานใหม่เรียบร้อยแล้ว';
        }

        session()->flash('success', $message);
        return redirect()->route('backend.work-types.index');
    }

    public function removeExistingImage()
    {
        if ($this->isEditMode && $this->existingImageUrl) {
            $relativePath = str_replace(asset('storage') . '/', '', $this->existingImageUrl);
            Storage::disk('public')->delete($relativePath);
            
            $this->workType->update(['image' => null]);
            $this->existingImageUrl = null;
        }
    }

    public function render()
    {
        return view('livewire.backend.work-type.form');
    }
}
