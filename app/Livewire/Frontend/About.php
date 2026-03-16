<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('frontend.layout')]
class About extends Component
{
    public function render()
    {
        return view('livewire.frontend.about')->title('เกี่ยวกับแอปพลิเคชัน (About)');
    }
}
