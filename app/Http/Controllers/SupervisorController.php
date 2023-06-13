<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    /**
     * Show the Supervisor index.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('supervisor.index');
    }
}
