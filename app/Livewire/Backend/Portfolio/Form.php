<?php

namespace App\Livewire\Backend\Portfolio;

use App\Models\Portfolio;
use App\Models\User;
use App\Models\WorkType;
use App\Services\UserImageService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.backend')]
class Form extends Component
{
    use WithFileUploads;

    public ?Portfolio $portfolio = null;
    public $isEditMode = false;

    // Form fields
    public $title = '';
    public $description = '';
    public $user_id = '';
    public $work_type_id = '';
    
    // User Search fields
    public $userSearch = '';
    public $selectedUserName = '';
    
    // Image handling
    public $new_images = [];
    public $existingImages = [];

    public function mount(?Portfolio $portfolio = null)
    {
        if ($portfolio && $portfolio->exists) {
            $this->portfolio = $portfolio;
            $this->isEditMode = true;
            $this->title = $portfolio->title;
            $this->description = $portfolio->description;
            $this->user_id = $portfolio->user_id;
            $this->selectedUserName = $portfolio->user ? $portfolio->user->name . ' (' . $portfolio->user->email . ')' : '';
            $this->work_type_id = $portfolio->work_type_id;
            
            $this->existingImages = is_array($portfolio->images) ? $portfolio->images : [];
        }
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'work_type_id' => 'required|exists:work_types,id',
            'new_images.*' => 'nullable|image|max:5120', // 5MB per image
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'กรุณากรอกชื่อแฟ้มผลงาน',
            'description.required' => 'กรุณากรอกรายละเอียดผลงาน',
            'user_id.required' => 'กรุณาเลือกเจ้าของผลงาน',
            'work_type_id.required' => 'กรุณาเลือกประเภทงาน',
            'new_images.*.image' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น',
            'new_images.*.max' => 'ขนาดรูปภาพต้องไม่เกิน 5MB',
        ];
    }

    public function save(UserImageService $imageService)
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'work_type_id' => $this->work_type_id,
        ];

        // Combine existing images and newly uploaded ones
        $finalImages = $this->existingImages;

        if (!empty($this->new_images)) {
            foreach ($this->new_images as $image) {
                $uploadResult = $imageService->handlePortfolioUpload($image);
                if (isset($uploadResult['status']) && $uploadResult['status']) {
                    $finalImages[] = $uploadResult['url'];
                }
            }
        }

        $data['images'] = $finalImages;

        if ($this->isEditMode) {
            $this->portfolio->update($data);
            $message = 'อัปเดตแฟ้มผลงานเรียบร้อยแล้ว';
        } else {
            Portfolio::create($data);
            $message = 'สร้างแฟ้มผลงานใหม่เรียบร้อยแล้ว';
        }

        session()->flash('success', $message);
        return redirect()->route('backend.portfolios.index');
    }

    public function removeExistingImage($index)
    {
        if (isset($this->existingImages[$index])) {
            $imageUrl = $this->existingImages[$index];
            $relativePath = str_replace(asset('storage') . '/', '', $imageUrl);
            Storage::disk('public')->delete($relativePath);
            
            unset($this->existingImages[$index]);
            // Re-index array
            $this->existingImages = array_values($this->existingImages);
            
            if ($this->isEditMode) {
                $this->portfolio->update(['images' => $this->existingImages]);
            }
        }
    }

    public function removeNewImage($index)
    {
        if (isset($this->new_images[$index])) {
            unset($this->new_images[$index]);
            $this->new_images = array_values($this->new_images);
        }
    }

    public function selectUser($userId, $userName, $userEmail)
    {
        $this->user_id = $userId;
        $this->selectedUserName = $userName . ' (' . $userEmail . ')';
        $this->userSearch = ''; // Clear search after selection
    }

    public function clearUser()
    {
        $this->user_id = '';
        $this->selectedUserName = '';
        $this->userSearch = '';
    }

    public function render()
    {
        $searchResults = collect();
        if (strlen($this->userSearch) >= 2) {
            $searchResults = User::where('name', 'like', '%' . $this->userSearch . '%')
                ->orWhere('email', 'like', '%' . $this->userSearch . '%')
                ->select('id', 'name', 'email')
                ->take(10)
                ->get();
        }

        return view('livewire.backend.portfolio.form', [
            'searchResults' => $searchResults,
            'workTypes' => WorkType::orderBy('title')->get(),
        ])->title($this->isEditMode ? 'แก้ไขแฟ้มผลงาน (Edit Portfolio)' : 'เพิ่มแฟ้มผลงาน (Create Portfolio)');
    }
}
