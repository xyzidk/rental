@extends('layout')
@section('content')
@if(session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="search-and-list">
    <form method="POST" action="{{ route('car.listCars') }}">
        @csrf
        From: <input type="date" name="date_from" value="{{ old('date_from', $date_from ?? '') }}"> <br>
        To: <input type="date" name="date_to" value="{{ old('date_to', $date_to ?? '') }}"> <br>
        <button type="submit">Submit</button>
    </form>

    @if(isset($cars))
        <div class="car-list">
            @if($cars->count())
                <h2>Available Cars:</h2>
                <ul>
                @foreach($cars as $car)
                    <li style="display: flex; align-items: center; gap: 16px;">
    <a href="{{ route('reservation.create', [
        'car_id' => $car->id,
        'date_from' => $date_from,
        'date_to' => $date_to]) }}">
        <img src="{{ Storage::url($car->image_path) }}" alt="Car" width="50" height="50">
    </a>
    <span>
        <strong>{{ $car->type }}</strong> - {{ $car->rental_price }} $/day
    </span>
</li>
                @endforeach
                </ul>
            @else
                <p>No cars available for the selected dates.</p>
            @endif
        </div>
    @endif
</div>
@endsection