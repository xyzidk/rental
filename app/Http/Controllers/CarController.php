<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\Storage;


class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::all();
        return view('car.index', compact('cars'));
    }

    public function deactivate(string $id)
    {
        $car = Car::findOrFail($id);
        if ($car->status === 'reserved') {
            return redirect()->route('car.index')->with('error', 'Cannot deactivate a reserved car.');
        }
        $car->status = 'deactivated';
        $car->save();
        return redirect()->route('car.index')->with('success', 'Car deactivated successfully.');
    }

   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('car.create');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
            'type' => 'required|string|max:255',
            'plate_number' => 'required|string|max:20|unique:car,plate_number',
            'rental_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);
        $car = new Car();
        $car->type = $request->input('type');
        $car->plate_number = $request->input('plate_number');
        $car->rental_price = $request->input('rental_price');
        if ($request->hasFile('image')) {
            $car->image_path = $request->file('image')->store("cars/{$car->id}", 'public');
        }
        $car->status = $request->input('status');
        $car->save();
        return redirect()->route('car.index')->with('success', 'Car created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $car = Car::findOrFail($id);
        return view('car.show', compact('car'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $car = Car::findOrFail($id);
        return view('car.edit', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'plate_number' => 'required|string|max:20|unique:car,plate_number,' . $id,
            'rental_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);
        $car = Car::findOrFail($id);
        $car->type = $request->input('type');
        $car->plate_number = $request->input('plate_number');
        $car->rental_price = $request->input('rental_price');
        $car->status = $request->input('status', $car->status);
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($car->image_path);
            $car->image_path = $request->file('image')->store("cars/", 'public');
        }
        $car->save();
        return redirect()->route('car.index')->with('success', 'Car updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $car = Car::findOrFail($id);

        if ($car->status === 'reserved') {
           return redirect()->route('car.index')->with('error', 'Cannot delete a reserved car.');
        }
        
        Storage::disk('public')->delete($car->image_path);

        $car->delete();
        return redirect()->route('car.index')->with('success', 'Car deleted successfully.');
    }
}
