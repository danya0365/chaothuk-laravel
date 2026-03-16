<?php

namespace App\Livewire\Frontend;

use App\Models\Recruit;
use App\Models\RecruitBooking;
use App\Models\Work;
use App\Models\WorkBooking;
use App\Models\WorkSession;
use Livewire\Component;

class GlobalAlerts extends Component
{
    public function dismiss(string $hash)
    {
        session()->put('dismissed_global_alert_hash', $hash);
    }

    public function render()
    {
        if (!auth()->check()) {
            return view('livewire.frontend.global-alerts', [
                'pendingOrdersCount' => 0,
                'pendingRecruitsCount' => 0,
                'activeSessions' => [],
                'shouldShow' => false,
            ]);
        }

        $uid = auth()->id();
        
        // Check for pending orders for the seller
        $myWorkIds = Work::where('author_id', $uid)->pluck('id');
        
        $pendingOrdersCount = WorkBooking::whereIn('work_id', $myWorkIds)
            ->where('booking_status', 'waiting-to-confirm')
            ->count();
            
        // Check for pending recruit applicants for the buyer
        $myRecruitIds = Recruit::where('author_id', $uid)->pluck('id');
        
        $pendingRecruitsCount = RecruitBooking::whereIn('recruit_id', $myRecruitIds)
            ->where('booking_status', 'waiting-to-confirm')
            ->count();
            
        // Check for active sessions (as buyer or seller)
        $activeSessions = WorkSession::with('sessionable')
            ->where(function($q) use ($uid) {
                $q->where('worker_id', $uid)->orWhere('customer_id', $uid);
            })
            ->where('status', 'active')
            ->take(3)
            ->get();

        // Generate a hash representing the CURRENT state of alerts
        $stateString = "pending:{$pendingOrdersCount}|recruits:{$pendingRecruitsCount}|sessions:";
        foreach ($activeSessions as $s) {
            $stateString .= "{$s->id}-{$s->updated_at->timestamp},";
        }
        $currentHash = md5($stateString);

        // Check if the user has dismissed this specific state
        $dismissedHash = session('dismissed_global_alert_hash');
        
        // Only show if there's actually something to alert AND the user hasn't dismissed this exact state
        $hasAlerts = $pendingOrdersCount > 0 || $pendingRecruitsCount > 0 || $activeSessions->isNotEmpty();
        $shouldShow = $hasAlerts && ($currentHash !== $dismissedHash);

        return view('livewire.frontend.global-alerts', [
            'pendingOrdersCount' => $pendingOrdersCount,
            'pendingRecruitsCount' => $pendingRecruitsCount,
            'activeSessions' => $activeSessions,
            'uid' => $uid,
            'currentHash' => $currentHash,
            'shouldShow' => $shouldShow,
        ]);
    }
}
