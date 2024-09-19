<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\CronLog;
use App\Models\IssuePoint;
use App\Models\IssuePointStatusLog;
use App\Models\PointTransactionLog;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Models\UserPointLog;
use App\Traits\SelectOption;

class ReportController extends Controller
{
    use SelectOption;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.report.index');
    }

    public function getPointLogs()
    {
        $post = request()->all();
        $startDateString = $post['start_date'] ?? null;
        $endDateString = $post['end_date'] ?? null;
        $startDate = $startDateString ? \Carbon\Carbon::parse($startDateString) : null;
        $endDate = $endDateString ? \Carbon\Carbon::parse($endDateString) : null;

        $query = UserPointLog::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        $pointLogs = $query->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.report.point-logs', compact('pointLogs'))
            ->with('i', (request()->input('page', 1) - 1) * $pointLogs->perPage());
    }

    public function getIssuePoints()
    {
        $post = request()->all();
        $startDateString = $post['start_date'] ?? null;
        $endDateString = $post['end_date'] ?? null;
        $startDate = $startDateString ? \Carbon\Carbon::parse($startDateString) : null;
        $endDate = $endDateString ? \Carbon\Carbon::parse($endDateString) : null;

        $query = IssuePoint::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        $issuePoints = $query->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.report.issue-points', compact('issuePoints'))
            ->with('i', (request()->input('page', 1) - 1) * $issuePoints->perPage());
    }

    public function getIssuePointStatusLogs()
    {
        $post = request()->all();
        $startDateString = $post['start_date'] ?? null;
        $endDateString = $post['end_date'] ?? null;
        $startDate = $startDateString ? \Carbon\Carbon::parse($startDateString) : null;
        $endDate = $endDateString ? \Carbon\Carbon::parse($endDateString) : null;

        $query = IssuePointStatusLog::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        $issuePointStatusLogs = $query->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.report.issue-point-status-logs', compact('issuePointStatusLogs'))
            ->with('i', (request()->input('page', 1) - 1) * $issuePointStatusLogs->perPage());
    }

    public function getUserActivityLogs()
    {
        $post = request()->all();
        $startDateString = $post['start_date'] ?? null;
        $endDateString = $post['end_date'] ?? null;
        $startDate = $startDateString ? \Carbon\Carbon::parse($startDateString) : null;
        $endDate = $endDateString ? \Carbon\Carbon::parse($endDateString) : null;

        $query = UserActivityLog::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        $userActivityLogs = $query->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.report.user-activity-logs', compact('userActivityLogs'))
            ->with('i', (request()->input('page', 1) - 1) * $userActivityLogs->perPage());
    }

    public function getCronLogs()
    {
        $post = request()->all();
        $startDateString = $post['start_date'] ?? null;
        $endDateString = $post['end_date'] ?? null;
        $startDate = $startDateString ? \Carbon\Carbon::parse($startDateString) : null;
        $endDate = $endDateString ? \Carbon\Carbon::parse($endDateString) : null;

        $query = CronLog::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        $cronLogs = $query->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.report.cron-logs', compact('cronLogs'))
            ->with('i', (request()->input('page', 1) - 1) * $cronLogs->perPage());
    }

    public function getPointTransactionLogs()
    {
        $post = request()->all();
        $startDateString = $post['start_date'] ?? null;
        $endDateString = $post['end_date'] ?? null;
        $startDate = $startDateString ? \Carbon\Carbon::parse($startDateString) : null;
        $endDate = $endDateString ? \Carbon\Carbon::parse($endDateString) : null;

        $query = PointTransactionLog::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        $pointTransactionLogs = $query->orderBy('id', 'desc')->paginate()->withQueryString();

        return view('backend.report.point-transaction-logs', compact('pointTransactionLogs'))
            ->with('i', (request()->input('page', 1) - 1) * $pointTransactionLogs->perPage());
    }
}