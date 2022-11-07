<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationStoreRequest;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = Reservation::all();
        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.reservations.create', $this->lists());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReservationStoreRequest $request)
    {
        $table = Table::findOrFail($request->table_id); // Table to compare date and seats validation

        // Validate guest_number for selected table
        if ($request->guest_number > $table->guest_number) {
            return back()->with('warning', 'This table does not have enough seats ('.$table->guest_number.')');
        }

        $request_date = Carbon::parse($request->datetime);
        foreach ($table->reservations as $reservation) {
            if ($reservation->datetime->format('Y-m-d') == $request_date->format('Y-m-d')) {
                return back()->with('warning', 'This table is already reserved for this date');
            }
        }

        // TODO validate time 1 hour after or before

        Reservation::create($request->validated());

        return to_route('admin.reservations.index')
            ->with('success', "Reservation $request->name created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        return view('admin.reservations.edit', array_merge(['reservation' => $reservation], $this->lists()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReservationStoreRequest $request, Reservation $reservation)
    {
        // Validate guest_number for selected table
        $table = Table::findOrFail($request->table_id);
        if ($request->guest_number > $table->guest_number) {
            return back()->with('warning', 'This table does not have enough seats ('.$table->guest_number.')');
        }

        $reservation->update($request->validated());

        return to_route('admin.reservations.index')
            ->with('success', "Reservation $request->name updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return to_route('admin.reservations.index')
            ->with('success', "Reservation $reservation->name deleted");
    }

    private function lists()
    {
        return [
            'tables' => Table::where(['status_id' => 2])->get(['id', 'name', 'guest_number']), // Only Available (status_id = 2) tables
        ];
    }
}
