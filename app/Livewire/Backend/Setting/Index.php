<?php

namespace App\Livewire\Backend\Setting;

use App\Models\Configuration;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.backend')]
class Index extends Component
{
    public $settings = [];
    
    // We store the models to iterate over them in the view
    public $configs;

    public function mount()
    {
        $this->loadConfigs();
    }

    public function loadConfigs()
    {
        $this->configs = Configuration::orderBy('id')->get();
        foreach ($this->configs as $config) {
            $this->settings[$config->slug] = $config->value;
        }
    }

    public function save()
    {
        // Simple validation to ensure 'required' fields are present
        // Since we are creating a dynamic admin form, we bypass strict Livewire rules arrays
        $hasError = false;

        foreach ($this->configs as $config) {
            $val = $this->settings[$config->slug] ?? null;
            
            // Text, url, option usually shouldn't be empty in basic setup
            if ($config->value_type !== 'boolean' && empty($val)) {
                $this->addError('settings.'.$config->slug, 'กรุณากรอกข้อมูล ' . $config->name);
                $hasError = true;
            }
        }

        // If validation fails, stop execution
        if ($hasError) {
            session()->flash('error', 'กรุณาตรวจสอบข้อมูลให้ครบถ้วน');
            return;
        }

        // Save back to database
        foreach ($this->configs as $config) {
            if (array_key_exists($config->slug, $this->settings)) {
                // For boolean, ensure it's saved as "1" or "0" string depending on how it's handled,
                // but the form toggle logic binds it as boolean. We cast to string.
                $val = (string)$this->settings[$config->slug];
                $config->update(['value' => $val]);
            }
        }

        session()->flash('success', 'บันทึกการตั้งค่าระบบเรียบร้อยแล้ว');
        $this->loadConfigs(); // Refresh
    }

    public function render()
    {
        return view('livewire.backend.setting.index');
    }
}
