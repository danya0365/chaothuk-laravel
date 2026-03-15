<?php

namespace App\Livewire\Backend\Setting;

use App\Models\Configuration;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.backend')]
class Index extends Component
{
    public $activeTab = 'general';
    public $settings = [];

    protected $rules = [
        'settings.*' => 'nullable',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $allSettings = Configuration::all();
        foreach ($allSettings as $setting) {
            $this->settings[$setting->slug] = $setting->value;
        }
        
        // Ensure default keys exist in the array to prevent undefined index errors in the view
        $defaultKeys = [
            'site_name', 'site_description', 'contact_email', 'contact_phone',
            'platform_fee_percent', 'minimum_withdrawal',
            'facebook_url', 'line_url'
        ];
        
        foreach ($defaultKeys as $key) {
            if (!isset($this->settings[$key])) {
                $this->settings[$key] = '';
            }
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function save()
    {
        $this->validate();

        foreach ($this->settings as $slug => $value) {
            $configuration = Configuration::where('slug', $slug)->first();
            
            if ($configuration) {
                $configuration->update(['value' => $value]);
            }
        }

        session()->flash('success', 'บันทึกการตั้งค่าระบบเรียบร้อยแล้ว');
    }

    public function render()
    {
        return view('livewire.backend.setting.index', [
            'title' => 'การตั้งค่าระบบ (System Settings)'
        ]);
    }
}
