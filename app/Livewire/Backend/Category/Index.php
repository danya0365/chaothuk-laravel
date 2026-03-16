<?php

namespace App\Livewire\Backend\Category;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.backend')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        
        // Prevent deletion if connected to works or recruits
        if ($category->works()->exists() || $category->recruits()->exists()) {
            session()->flash('error', 'ไม่สามารถลบหมวดหมู่นี้ได้ เนื่องจากมีงานหรือประกาศผูกอยู่');
            return;
        }

        $category->delete();
        session()->flash('success', 'ลบหมวดหมู่เรียบร้อยแล้ว');
    }

    public function render()
    {
        $query = Category::orderBy('name', 'asc');

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $categories = $query->paginate(10);

        return view('livewire.backend.category.index', compact('categories'));
    }
}
