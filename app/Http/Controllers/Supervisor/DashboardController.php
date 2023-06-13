<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the Dashboard index.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('supervisor.dashboard.index');
    }
}
