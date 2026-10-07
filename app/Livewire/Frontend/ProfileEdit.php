<?php

namespace App\Livewire\Frontend;

use App\Services\UserImageService;
use Illuminate\Http\UploadedFile;
use Livewire\Component;

class ProfileEdit extends Component
{
    public string $firstName = '';

    public string $lastName = '';

    public string $name = '';

    public string $mobilePhone = '';

    public string $location = '';

    public string $biography = '';

    public string $profileImage = '';

    public ?string $avatarError = null;

    public function mount(): void
    {
        $u = auth()->user();
        $this->firstName = $u->first_name ?? '';
        $this->lastName = $u->last_name ?? '';
        $this->name = $u->name ?? '';
        $this->mobilePhone = $u->mobile_phone ?? '';
        $this->location = $u->location ?? '';
        $this->biography = $u->biography ?? '';
        $this->profileImage = $u->profile_image ?? '';
    }

    public function removeAvatar(): void
    {
        $this->profileImage = '';
    }

    /**
     * Persist an avatar uploaded from the profile form.
     *
     * The blade hands over the cropped image as a base64 data URL, because the
     * cropper runs in the browser and has no File to give us.
     */
    public function uploadAvatar(string $dataUrl): void
    {
        $result = $this->storeAvatar($dataUrl);

        if ($url = $result['url'] ?? null) {
            $this->profileImage = $url;
            $this->avatarError = null;

            return;
        }

        $this->avatarError = $result['message'] ?? 'อัพโหลดรูปไม่สำเร็จ';
    }

    /**
     * @return array{url?: string, message?: string}
     */
    private function storeAvatar(string $dataUrl): array
    {
        $decoded = $this->decodeImageDataUrl($dataUrl);

        if ($decoded === null) {
            return ['message' => 'รูปที่เลือกไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง'];
        }

        $user = auth()->user();

        $result = app(UserImageService::class)->handleAvatarUpload(
            $decoded,
            $user,
        );

        if (! ($result['status'] ?? false)) {
            return ['message' => $result['message'] ?? 'อัพโหลดรูปไม่สำเร็จ'];
        }

        return ['url' => $result['url'] ?? null];
    }

    /**
     * Turn "data:image/webp;base64,..." into a real UploadedFile.
     */
    private function decodeImageDataUrl(string $dataUrl): ?UploadedFile
    {
        if (! preg_match('#^data:image/(png|jpe?g|webp);base64,#i', $dataUrl, $matches)) {
            return null;
        }

        $binary = base64_decode(substr($dataUrl, strlen($matches[0])), true);

        if ($binary === false || $binary === '') {
            return null;
        }

        $extension = strtolower($matches[1]) === 'jpeg' ? 'jpg' : strtolower($matches[1]);
        $file = tempnam(sys_get_temp_dir(), 'chaothuk-avatar-').'.'.$extension;

        if (file_put_contents($file, $binary) === false) {
            return null;
        }

        return new UploadedFile(
            $file,
            "avatar.{$extension}",
            "image/{$extension}",
            null,
            true,
        );
    }

    public function save(): void
    {
        $this->validate([
            'firstName' => 'required|min:1|max:100',
            'lastName' => 'required|min:1|max:100',
            'name' => 'required|min:2|max:100',
            'mobilePhone' => 'nullable|min:9|max:15',
            'location' => 'nullable|max:255',
            'biography' => 'nullable|max:1000',
        ], [
            'firstName.required' => 'กรุณากรอกชื่อจริง',
            'lastName.required' => 'กรุณากรอกนามสกุล',
            'name.required' => 'กรุณากรอกชื่อที่แสดง',
            'mobilePhone.min' => 'เบอร์โทรต้องมีอย่างน้อย 9 หลัก',
        ]);

        auth()->user()->update([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'name' => $this->name,
            'mobile_phone' => $this->mobilePhone ?: null,
            'location' => $this->location ?: null,
            'biography' => $this->biography ?: null,
            'profile_image' => $this->profileImage ?: null,
        ]);

        $this->redirect(route('frontend.profile'), navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.profile-edit')
            ->layout('frontend.layout', ['title' => 'แก้ไขโปรไฟล์ — Chaothuk']);
    }
}
