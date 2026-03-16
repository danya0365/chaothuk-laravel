<?php

namespace App\Livewire\Backend\User\Partials;

use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use App\Models\IssuePoint;
use App\Models\PointTransactionLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class PointsManager extends Component
{
    use WithPagination;

    public User $user;
    
    // Add/Deduct Form State
    public $showModal = false;
    public $actionType = 'add'; // 'add' or 'deduct'
    public $amount = 0;
    public $issue_point_id = '';
    public $note = '';

    protected function rules()
    {
        return [
            'amount' => 'required|numeric|min:1',
            'actionType' => 'required|in:add,deduct',
            'issue_point_id' => 'required|exists:issue_points,id',
            'note' => 'nullable|string|max:255',
        ];
    }

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function updatedIssuePointId($val)
    {
        if ($val) {
            $issue = IssuePoint::find($val);
            if ($issue && $issue->points) {
                // Auto-fill amount based on issue points definition
                $this->amount = abs($issue->points);
            }
        }
    }

    public function openModal($type = 'add')
    {
        $this->reset(['amount', 'note', 'issue_point_id']);
        $this->actionType = $type;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function saveTransaction()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $issuePointConfig = IssuePoint::find($this->issue_point_id);

            if ($this->actionType === 'add') {
                // Add points: Create a new UserPoint
                $userPoint = UserPoint::create([
                    'point_received' => $this->amount,
                    'point_available' => $this->amount,
                    'user_id' => $this->user->id,
                    'issue_point_id' => $issuePointConfig->id,
                    'expired_at' => $issuePointConfig->end_at, // Use end_at from issue config if applicable
                ]);

                $userPointLog = UserPointLog::create([
                    'points' => $this->amount,
                    'user_point_id' => $userPoint->id,
                    'action_user_id' => auth()->id(), // Admin who did it
                ]);

                $transaction = new PointTransactionLog();
                $transaction->user_id = $this->user->id;
                $transaction->points = $this->amount;
                $transaction->user_point_logs = [$userPointLog];
                
                // Polymorphic relation to IssuePoint as the reason
                $transaction->transactionable()->associate($issuePointConfig); 
                
                $transaction->save();
            } else {
                // Deduct points concept
                // Find oldest available points and deduct them sequentially
                $pointsToDeduct = $this->amount;
                $availablePointRecords = UserPoint::where('user_id', $this->user->id)
                    ->where('point_available', '>', 0)
                    ->orderBy('created_at', 'asc')
                    ->get();
                    
                $logs = [];

                foreach ($availablePointRecords as $record) {
                    if ($pointsToDeduct <= 0) break;

                    $deduct = min($record->point_available, $pointsToDeduct);
                    $record->point_available -= $deduct;
                    $record->save();

                    $log = UserPointLog::create([
                        'points' => -$deduct, // negative for deduction
                        'user_point_id' => $record->id,
                        'action_user_id' => auth()->id(),
                    ]);
                    $logs[] = $log;

                    $pointsToDeduct -= $deduct;
                }

                if ($pointsToDeduct > 0) {
                    throw new \Exception('คะแนนสะสมไม่พอสำหรับการหัก');
                }

                $transaction = new PointTransactionLog();
                $transaction->user_id = $this->user->id;
                $transaction->points = -$this->amount;
                $transaction->user_point_logs = $logs;
                $transaction->transactionable()->associate($issuePointConfig); // Record deduction reason
                $transaction->save();
            }

            DB::commit();

            session()->flash('success', 'ปรับปรุงคะแนนเรียบร้อยแล้ว');
            $this->closeModal();
            $this->user->refresh(); // Refresh to update availablePoints
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Load transaction logs with pagination
        $transactions = PointTransactionLog::where('user_id', $this->user->id)
            ->with('transactionable')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Load active issue points for the dropdown
        $issuePoints = IssuePoint::where('status', 'approve')->get();

        return view('livewire.backend.user.partials.points-manager', [
            'transactions' => $transactions,
            'issuePoints' => $issuePoints,
        ]);
    }
}
