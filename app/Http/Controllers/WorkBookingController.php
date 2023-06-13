<?php

namespace App\Http\Controllers;

use App\Models\WorkBooking;
use Illuminate\Http\Request;

/**
 * Class WorkBookingController
 * @package App\Http\Controllers
 */
class WorkBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workBookings = WorkBooking::paginate();

        return view('work-booking.index', compact('workBookings'))
            ->with('i', (request()->input('page', 1) - 1) * $workBookings->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $workBooking = new WorkBooking();
        return view('work-booking.create', compact('workBooking'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(WorkBooking::$rules);

        $workBooking = WorkBooking::create($request->all());

        return redirect()->route('work-bookings.index')
            ->with('success', 'WorkBooking created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $workBooking = WorkBooking::find($id);

        return view('work-booking.show', compact('workBooking'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $workBooking = WorkBooking::find($id);

        return view('work-booking.edit', compact('workBooking'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  WorkBooking $workBooking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkBooking $workBooking)
    {
        request()->validate(WorkBooking::$rules);

        $workBooking->update($request->all());

        return redirect()->route('work-bookings.index')
            ->with('success', 'WorkBooking updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $workBooking = WorkBooking::find($id)->delete();

        return redirect()->route('work-bookings.index')
            ->with('success', 'WorkBooking deleted successfully');
    }
}
