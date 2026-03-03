<?php

namespace App\Livewire\Frontend;

use App\Models\Portfolio;
use App\Models\WorkType;
use Livewire\Component;

class PortfolioEdit extends Component
{
    public int $id;
    public string $title = '';
    public string $description = '';
    public ?int $workTypeId = null;
    public array $workTypes = [];
    public array $images = [];

    public function mount(int $id): void
    {
        $portfolio = Portfolio::where('user_id', auth()->id())->findOrFail($id);
        $this->id = $id;
        $this->title = $portfolio->title;
        $this->description = $portfolio->description ?? '';
        $this->workTypeId = $portfolio->work_type_id;
        $this->images = is_array($portfolio->images) ? $portfolio->images : [];
        $this->workTypes = WorkType::orderBy('title')->get()->toArray();
    }

    public function removeImage(int $index): void
    {
        $images = $this->images;
        array_splice($images, $index, 1);
        $this->images = array_values($images);
    }

    public function deletePortfolio(): void
    {
        $portfolio = Portfolio::where('user_id', auth()->id())->findOrFail($this->id);
        $portfolio->delete();
        $this->redirect(route('frontend.portfolios'), navigate: true);
    }

    public function save(): void
    {
        $this->validate([
            'title'       => 'required|min:3|max:255',
            'description' => 'required|min:5',
            'workTypeId'  => 'required|exists:work_types,id',
            'images'      => 'array|min:1',
        ], [
            'title.required'       => 'กรุณากรอกชื่อผลงาน',
            'title.min'            => 'ชื่อผลงานต้องมีอย่างน้อย 3 ตัวอักษร',
            'description.required' => 'กรุณากรอกรายละเอียดผลงาน',
            'description.min'      => 'รายละเอียดต้องมีอย่างน้อย 5 ตัวอักษร',
            'workTypeId.required'  => 'กรุณาเลือกประเภทงาน',
            'images.min'           => 'กรุณาอัพโหลดรูปภาพอย่างน้อย 1 รูป',
        ]);

        $portfolio = Portfolio::where('user_id', auth()->id())->findOrFail($this->id);
        $portfolio->update([
            'title'        => $this->title,
            'description'  => $this->description,
            'work_type_id' => $this->workTypeId,
            'images'       => $this->images,
        ]);

        $this->redirect(route('frontend.portfolios'), navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.portfolio-edit')
            ->layout('frontend.layout', ['title' => 'แก้ไขผลงาน — Chaothuk']);
    }
}
