<?php

namespace App\Livewire\Backend\User\Partials;

use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserPointLog;
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
    public $note = '';

    protected $rules = [
        'amount' => 'required|integer|min:1',
        'actionType' => 'required|in:add,deduct',
        'note' => 'nullable|string|max:255',
    ];

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function openModal($type = 'add')
    {
        $this->reset(['amount', 'note']);
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
            if ($this->actionType === 'add') {
                // Add points: Create a new UserPoint
                $userPoint = UserPoint::create([
                    'point_received' => $this->amount,
                    'point_available' => $this->amount,
                    'user_id' => $this->user->id,
                    'expired_at' => null,
                ]);

                $userPointLog = UserPointLog::create([
                    'points' => $this->amount,
                    'user_point_id' => $userPoint->id,
                    'action_user_id' => auth()->id(), // Admin who did it
                ]);

                $transaction = new PointTransactionLog();
                $transaction->user_id = $this->user->id;
                $transaction->points = $this->amount;
                // Note: storing note in points temporarily or skipping morphological relation if not strict
                // we leave transactionable null since it's a manual adjustment
                $transaction->user_point_logs = [$userPointLog];
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

        return view('livewire.backend.user.partials.points-manager', [
            'transactions' => $transactions,
        ]);
    }
}
