<?php

namespace App\Livewire\Backend\Point;

use App\Models\IssuePoint;
use App\Enums\IssueType;
use App\Enums\IssueStatus;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('กำหนดแคมเปญพอยท์ - Admin')]
class IssueForm extends Component
{
    public ?IssuePoint $issue = null;

    public $name = '';
    public $slug = '';
    public $desc = '';
    public $points = 0;
    public $type = 'one_time';
    public $status = 'submit';
    public $start_at = null;
    public $end_at = null;
    
    // Using string values from Enums for easier binding
    public $availableTypes = [];
    public $availableStatuses = [];

    public function mount(IssuePoint $issue = null)
    {
        $this->availableTypes = IssueType::values();
        $this->availableStatuses = IssueStatus::values();

        if ($issue && $issue->exists) {
            $this->issue = $issue;
            $this->name = $issue->name;
            $this->slug = $issue->slug;
            $this->desc = $issue->desc;
            $this->points = $issue->points;
            $this->type = $issue->type;
            $this->status = $issue->status;
            
            // Format dates for HTML5 datetime-local input if they exist
            $this->start_at = $issue->start_at ? \Carbon\Carbon::parse($issue->start_at)->format('Y-m-d\TH:i') : null;
            $this->end_at = $issue->end_at ? \Carbon\Carbon::parse($issue->end_at)->format('Y-m-d\TH:i') : null;
        } else {
            $this->type = IssueType::ONE_TIME->value;
            $this->status = IssueStatus::APPROVE->value; // Default to approve for manual creation by admin
        }
    }

    public function rules()
    {
        $id = $this->issue ? $this->issue->id : null;
        return [
            'name' => 'required|min:2',
            'slug' => ['required', 'alpha_dash', Rule::unique('issue_points', 'slug')->ignore($id)],
            'desc' => 'required|string',
            'points' => 'required|numeric|not_in:0',
            'type' => ['required', Rule::in($this->availableTypes)],
            'status' => ['required', Rule::in($this->availableStatuses)],
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ];
    }

    public function updatedName($value)
    {
        if (!$this->issue) {
            $this->slug = \Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => strtolower($this->slug),
            'desc' => $this->desc,
            'points' => $this->points,
            'type' => $this->type,
            'status' => $this->status,
            'user_id' => auth()->id(), // Record the admin who created/updated this issue
            'start_at' => $this->start_at ? \Carbon\Carbon::parse($this->start_at)->format('Y-m-d H:i:s') : null,
            'end_at' => $this->end_at ? \Carbon\Carbon::parse($this->end_at)->format('Y-m-d H:i:s') : null,
        ];

        if ($this->issue && $this->issue->exists) {
            // Re-assign user_id to last editor
            $this->issue->update($data);
            session()->flash('success', 'อัปเดตแคมเปญพอยท์สำเร็จ');
        } else {
            IssuePoint::create($data);
            session()->flash('success', 'สร้างแคมเปญพอยท์สำเร็จ');
        }

        return $this->redirectRoute('backend.points.issues.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.backend.point.issue-form');
    }
}
