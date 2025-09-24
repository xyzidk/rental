@extends('layout')

@section('content')

<div style="margin-left: 35%">
<h2>Reservation details</h2>
Car ID: {{$reservation->car_id}}<br>
Phone Number: {{ $reservation->phone_number }}<br>
Email: {{ $reservation->email }}<br>
Address: {{ $reservation->address }}<br>
Start Date: {{ $reservation->date_from }}<br>
End Date: {{ $reservation->date_to }}<br>
Days: {{ $reservation->days }}<br>
Total Price: {{ $reservation->total_price }}<br>
</div>
<a href="{{ route('reservation.index') }}" style="display:block; text-align:center; margin: 24px 0 0 0;"> Back</a>
@endsection
