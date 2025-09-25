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

<h2>Reservation: {{ $car->type }}</h2>
<form method="POST" action="{{ route('reservation.store') }}">
    @csrf
    <input type="hidden" name="car_id" value="{{ $car->id }}">
    <table>
        <tr>
            <td>Name:</td>
            <td><input type="text" name="name" required></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><input type="email" name="email" required></td>
        </tr>
        <tr>
            <td>Address:</td>
            <td><input type="text" name="address" required></td>
        </tr>
        <tr>
            <td>Phone:</td>
            <td><input type="text" name="phone" required></td>
        </tr>
        <tr>
            <td>From:</td>
            <td><input type="date" name="date_from" value="{{ $date_from }}" readonly></td>
        </tr>
        <tr>
            <td>To:</td>
            <td><input type="date" name="date_to" value="{{ $date_to }}" readonly></td>
        </tr>
        <tr>
            <td>Days:</td>
            <td><input type="text" name="days" value="{{ $days }}" readonly></td>
        </tr>
        <tr>
            <td>Total price:</td>
            <td><input type="number" name="total_price" value="{{ $total_price }}" readonly></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center; padding-top:16px;">
                <button type="submit" style="width:200px;">Submit</button>
            </td>
        </tr>
    </table>
</form>
<form  method="GET" action="{{ url('/') }}" style="width: 100px">
    @csrf
    <button type="submit">Cancel</button>
</form>
@endsection
