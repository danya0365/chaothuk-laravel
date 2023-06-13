<?php

namespace App\Http\Controllers;

use App\Models\RecruitBooking;
use Illuminate\Http\Request;

/**
 * Class RecruitBookingController
 * @package App\Http\Controllers
 */
class RecruitBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recruitBookings = RecruitBooking::paginate();

        return view('recruit-booking.index', compact('recruitBookings'))
            ->with('i', (request()->input('page', 1) - 1) * $recruitBookings->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $recruitBooking = new RecruitBooking();
        return view('recruit-booking.create', compact('recruitBooking'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(RecruitBooking::$rules);

        $recruitBooking = RecruitBooking::create($request->all());

        return redirect()->route('recruit-bookings.index')
            ->with('success', 'RecruitBooking created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recruitBooking = RecruitBooking::find($id);

        return view('recruit-booking.show', compact('recruitBooking'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $recruitBooking = RecruitBooking::find($id);

        return view('recruit-booking.edit', compact('recruitBooking'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  RecruitBooking $recruitBooking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RecruitBooking $recruitBooking)
    {
        request()->validate(RecruitBooking::$rules);

        $recruitBooking->update($request->all());

        return redirect()->route('recruit-bookings.index')
            ->with('success', 'RecruitBooking updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $recruitBooking = RecruitBooking::find($id)->delete();

        return redirect()->route('recruit-bookings.index')
            ->with('success', 'RecruitBooking deleted successfully');
    }
}
