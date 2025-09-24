<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Reservation;
use DateTime;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::all();
        return view('reservation.index', compact('reservations'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
   public function create(Request $request)
   {
    $car = Car::find($request->car_id);
    $date_from = $request->date_from;
    $date_to = $request->date_to;
    $days = $date_from && $date_to ? (new DateTime($date_from))->diff(new \DateTime($date_to))->days + 1 : 1;
    $total_price = $car ? $car->rental_price * $days : 0;

    return view('reservation.create', compact('car', 'date_from', 'date_to', 'days', 'total_price'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:car,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'days' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        $reservation = new Reservation();
        $reservation->car_id = $request->input('car_id');
        $reservation->name = $request->input('name');
        $reservation->email = $request->input('email');
        $reservation->address = $request->input('address');
        $reservation->phone = $request->input('phone');
        $reservation->date_from = $request->input('date_from');
        $reservation->date_to = $request->input('date_to');
        $reservation->days = $request->input('days');
        $reservation->total_price = $request->input('total_price');
        $reservation->save();
        
        
        $car = Car::find($reservation->car_id);
        if ($car) {
            $car->status = 'reserved';
            $car->save();
        }
        if(env('IS_ADMIN')) {
            return redirect()->route('reservation.index')->with('success', 'Reservation created successfully.');
        }
        return redirect('/')->with('success', 'Reservation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservation.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        $car = Car::find($reservation->car_id);
        if ($car) {
            $car->status = 'not_reserved';
            $car->save();
        }
        $reservation->delete();
        return redirect()->route('reservation.index')->with('success', 'Reservation deleted successfully.');

    }
}
