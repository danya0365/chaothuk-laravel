<?php

namespace App\Livewire\Frontend;

use App\Models\Post;
use App\Models\Portfolio;
use App\Models\Recruit;
use App\Models\RecruitBooking;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkBooking;
use Livewire\Component;

class Profile extends Component
{
    public string $tab = 'info';

    // Editable fields
    public string $firstName = '';
    public string $lastName = '';
    public string $name = '';
    public string $mobilePhone = '';
    public string $location = '';
    public string $biography = '';
    public string $profileImage = '';

    public bool $editing = false;
    public ?string $saveMessage = null;

    // Tab data
    public ?array $myWorks = null;
    public ?array $myRecruits = null;
    public ?array $myWorkBookings = null;
    public ?array $myRecruitBookings = null;
    public ?array $myReviews = null;
    public ?array $myPortfolios = null;

    public function mount(): void
    {
        $this->loadUserData();
        $this->loadTab();
    }

    protected function loadUserData(): void
    {
        $u = auth()->user();
        $this->firstName    = $u->first_name ?? '';
        $this->lastName     = $u->last_name ?? '';
        $this->name         = $u->name ?? '';
        $this->mobilePhone  = $u->mobile_phone ?? '';
        $this->location     = $u->location ?? '';
        $this->biography    = $u->biography ?? '';
        $this->profileImage = $u->profile_image ?? '';
    }

    public function toggleEdit(): void
    {
        $this->editing = !$this->editing;
        $this->saveMessage = null;
        if (!$this->editing) {
            $this->loadUserData(); // reset changes
        }
    }

    public function saveProfile(): void
    {
        $this->validate([
            'firstName'   => 'required|min:1|max:100',
            'lastName'    => 'required|min:1|max:100',
            'name'        => 'required|min:2|max:100',
            'mobilePhone' => 'nullable|min:9|max:15',
            'location'    => 'nullable|max:255',
            'biography'   => 'nullable|max:1000',
        ], [
            'firstName.required'  => 'กรุณากรอกชื่อ',
            'lastName.required'   => 'กรุณากรอกนามสกุล',
            'name.required'       => 'กรุณากรอกชื่อที่แสดง',
            'mobilePhone.min'     => 'เบอร์โทรต้องมีอย่างน้อย 9 หลัก',
        ]);

        auth()->user()->update([
            'first_name'    => $this->firstName,
            'last_name'     => $this->lastName,
            'name'          => $this->name,
            'mobile_phone'  => $this->mobilePhone ?: null,
            'location'      => $this->location ?: null,
            'biography'     => $this->biography ?: null,
            'profile_image' => $this->profileImage ?: null,
        ]);

        $this->editing = false;
        $this->saveMessage = '✅ บันทึกโปรไฟล์เรียบร้อย';
    }

    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
        $this->loadTab();
    }

    protected function loadTab(): void
    {
        $uid = auth()->id();
        match ($this->tab) {
            'works'      => $this->myWorks = Work::with(['province', 'workType'])
                                ->where('author_id', $uid)->latest()->limit(20)->get()->toArray(),
            'recruits'   => $this->myRecruits = Recruit::with(['province', 'workType'])
                                ->where('author_id', $uid)->latest()->limit(20)->get()->toArray(),
            'bookings'   => $this->loadBookings($uid),
            'reviews'    => $this->myReviews = Post::where('author_id', $uid)
                                ->whereNull('parent_id')->where('rating', '>', 0)
                                ->latest()->limit(20)->get()->toArray(),
            'portfolios' => $this->myPortfolios = Portfolio::with('workType')
                                ->where('user_id', $uid)->latest()->limit(20)->get()->toArray(),
            default      => null,
        };
    }

    protected function loadBookings(int $uid): void
    {
        $this->myWorkBookings = WorkBooking::with(['work'])
            ->where('author_id', $uid)->latest()->limit(10)->get()->toArray();
        $this->myRecruitBookings = RecruitBooking::with(['recruit'])
            ->where('author_id', $uid)->latest()->limit(10)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.profile')
            ->layout('frontend.layout', ['title' => 'โปรไฟล์ — Chaothuk']);
    }
}
