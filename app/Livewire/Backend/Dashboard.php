<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserVerification;
use App\Models\Dispute;
use App\Models\UserReport;
use App\Models\WorkSession;
use App\Models\Work;
use App\Models\UserActivityLog;
use Carbon\Carbon;

#[Layout('layouts.backend')]
class Dashboard extends Component
{
    public function render()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        // --- Key Performance Indicators (KPIs) ---
        $metrics = [
            'total_users' => User::count(),
            'new_users_7d' => User::where('created_at', '>=', $sevenDaysAgo)->count(),
            
            'active_sessions' => WorkSession::where('status', 'active')->count(),
            'completed_sessions_7d' => WorkSession::where('status', 'completed')->where('updated_at', '>=', $sevenDaysAgo)->count(),
            
            'total_points' => UserPoint::sum('point_available'),
            
            'total_works' => Work::count(),
            'active_works' => Work::where('work_status', 'active')->count(),
        ];

        // --- Action Items (Urgent Alerts) ---
        $alerts = [
            'pending_verifications' => UserVerification::where('status', 'pending')->count(),
            'open_disputes' => Dispute::where('status', 'open')->count(),
            'pending_reports' => UserReport::where('status', 'pending')->count(),
        ];

        // --- Activity Feeds ---
        $latestActivities = UserActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $latestUsers = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('livewire.backend.dashboard', compact('metrics', 'alerts', 'latestActivities', 'latestUsers'))
            ->layout('layouts.backend', ['title' => 'ภาพรวมระบบแบบเจาะลึก (Enhanced Dashboard)']);
    }
}
