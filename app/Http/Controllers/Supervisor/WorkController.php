<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * Show the Work index.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('supervisor.work.index');
    }
}
