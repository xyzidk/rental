@extends('layout')

@section('content')
<div style="display: flex; align-items: flex-start; gap: 32px;margin-left:35%">
    <div>
        <h2>Car details</h2>
        Type: {{ $car->type }}<br>
        Plate Number: {{ $car->plate_number }}<br>
        Rental Price: {{ $car->rental_price }}<br>
        Status: {{ ['reserved' => 'Reserved',
            'deactivated' => 'Deactivated',
            'not_reserved' => 'Not Reserved'
            ][$car->status] ?? 'Unknown' }}<br>
    </div>
    <img src="{{ Storage::url($car->image_path) }}" alt="Car" width="200" height="200">
</div>
<a href="{{ route('car.index') }}" style="display:block; text-align:center; margin: 24px 0 0 0;"> Back</a>
@endsection
