<?php

namespace App\Livewire\Backend\Banner;

use App\Models\Banner;
use App\Enums\BannerType;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

#[Layout('layouts.backend')]
class Form extends Component
{
    use WithFileUploads;

    public ?Banner $banner = null;
    public $isEditMode = false;

    public $name = '';
    public $type = 'external_url';
    public $external_url = '';
    public $is_public = true;
    public $is_pinned = false;
    public $expired_at = '';
    public $has_expiration = false;
    
    public $image;
    public $existingImageUrl = null;

    public function mount(?Banner $banner = null)
    {
        if ($banner && $banner->exists) {
            $this->banner = $banner;
            $this->isEditMode = true;
            $this->name = $banner->name;
            $this->type = $banner->type;
            $this->external_url = $banner->external_url;
            $this->is_public = $banner->is_public;
            $this->is_pinned = $banner->is_pinned;
            $this->existingImageUrl = $banner->image_url;
            
            if ($banner->expired_at) {
                $this->has_expiration = true;
                $this->expired_at = Carbon::parse($banner->expired_at)->format('Y-m-d\TH:i');
            }
        } else {
            $this->isEditMode = false;
            $this->type = BannerType::EXTERNAL_URL->value;
        }
    }

    public function updatedHasExpiration($value)
    {
        if (!$value) {
            $this->expired_at = '';
        }
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:200|unique:banners,name' . ($this->isEditMode ? ',' . $this->banner->id : ''),
            'type' => 'required|string|in:' . implode(',', array_column(BannerType::cases(), 'value')),
            'external_url' => 'nullable|url|max:1000',
            'is_public' => 'boolean',
            'is_pinned' => 'boolean',
            'image' => ($this->isEditMode ? 'nullable' : 'required') . '|image|max:5120', // 5MB Max
            'has_expiration' => 'boolean',
        ];

        if ($this->has_expiration) {
            $rules['expired_at'] = 'required|date|after:today';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'กรุณากรอกชื่อแบนเนอร์',
            'name.unique' => 'ชื่อแบนเนอร์นี้มีอยู่ในระบบแล้ว',
            'external_url.url' => 'รูปแบบ URL ไม่ถูกต้อง',
            'image.required' => 'กรุณาอัปโหลดรูปภาพแบนเนอร์',
            'image.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพเท่านั้น',
            'image.max' => 'ขนาดรูปภาพต้องไม่เกิน 5MB',
            'expired_at.required' => 'กรุณาระบุวันหมดอายุ',
            'expired_at.after' => 'วันหมดอายุต้องเป็นวันที่ในอนาคต',
            'type.in' => 'ประเภทแบนเนอร์ไม่ถูกต้อง'
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'external_url' => $this->external_url,
            'is_public' => $this->is_public,
            'is_pinned' => $this->is_pinned,
            'expired_at' => $this->has_expiration ? Carbon::parse($this->expired_at) : null,
        ];

        if ($this->image) {
            if ($this->isEditMode && $this->existingImageUrl) {
                $relativePath = str_replace(asset('storage') . '/', '', $this->existingImageUrl);
                Storage::disk('public')->delete($relativePath);
            }
            
            $imageService = app(\App\Services\UserImageService::class);
            $uploadResult = $imageService->handleBannerUpload($this->image);
            
            if (isset($uploadResult['status']) && $uploadResult['status']) {
                $data['image_url'] = $uploadResult['url'];
            }
        }

        if ($this->isEditMode) {
            $this->banner->update($data);
            $message = 'อัปเดตข้อมูลแบนเนอร์เรียบร้อยแล้ว';
        } else {
            Banner::create($data);
            $message = 'สร้างแบนเนอร์ใหม่เรียบร้อยแล้ว';
        }

        session()->flash('success', $message);
        return redirect()->route('backend.banners.index');
    }

    public function removeExistingImage()
    {
        if ($this->isEditMode && $this->existingImageUrl) {
            $relativePath = str_replace(asset('storage') . '/', '', $this->existingImageUrl);
            Storage::disk('public')->delete($relativePath);
            
            $this->banner->update(['image_url' => '']); // Allow nullable conditionally in model context or temporary update
            $this->existingImageUrl = null;
        }
    }

    public function render()
    {
        return view('livewire.backend.banner.form');
    }
}
