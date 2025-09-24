<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Reservation;


class MainController extends Controller
{
    public function listCars(Request $request)
{
    $request->validate([
        'date_from' => 'required|date',
        'date_to' => 'required|date|after_or_equal:date_from',
    ]);

    $reservedCarIds = Reservation::where(function($q) use ($request) {
        $q->where('date_from', '<=', $request->date_to)
          ->where('date_to', '>=', $request->date_from);
    })->pluck('car_id');

    $cars = Car::whereNotIn('id', $reservedCarIds)
        ->where('status', 'not_reserved')
        ->get();

    return view('index', [
        'cars' => $cars,
        'date_from' => $request->date_from,
        'date_to' => $request->date_to,
    ]);
}
}
