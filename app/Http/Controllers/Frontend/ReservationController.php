<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\TableStatus;
use App\Rules\DateBetween;
use App\Rules\TimeBetween;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function stepOne(Request $request)
    {
        $reservation = $request->session()->get('reservation');
        $min_date = Carbon::today();
        $max_date = Carbon::now()->addWeek();
        return view('reservations.step-one', compact('reservation', 'min_date', 'max_date'));
    }

    public function storeStepOne(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'guest_number' => ['required'],
            'datetime' => [
                'required',
                'date',
                new DateBetween,
                new TimeBetween,
            ],
        ]);

        if (empty($request->session()->get(strtolower(Reservation::class)))) {
            $reservation = new Reservation();
        } else {
            $reservation = $request->session()->get(strtolower(Reservation::class));
        }

        $reservation->fill($validated);
        $request->session()->put(strtolower(Reservation::class), $reservation);

        return to_route('reservations.step-two');
    }

    public function stepTwo(Request $request)
    {
        $reservation = $request->session()->get(strtolower(Reservation::class));

        $reservations_table_ids = Reservation::orderBy('datetime')->get()
            ->filter(function($value) use($reservation){
                return $value->datetime->format('Y-m-d') == $reservation->datetime->format('Y-m-d');
            })->pluck('table_id');

        $tables = Table::where('status_id', 2)
            ->where('guest_number', '>=', $reservation->guest_number)
            ->whereNotIn('id', $reservations_table_ids)
            ->select('id', 'name', 'guest_number')
            ->get(); // Only Available TableStatus

        return view('reservations.step-two', compact('reservation', 'tables'));
    }

    public function storeStepTwo(Request $request)
    {
        $validated = $request->validate([
            'table_id' => ['required']
        ]);

        $reservation = $request->session()->get(strtolower(Reservation::class));
        $reservation->fill($validated);
        $reservation->save();
        $request->session()->forget(strtolower(Reservation::class));

        return to_route('reservations.thankyou');
    }
}
