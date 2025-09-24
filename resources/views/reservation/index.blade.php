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

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>Phone</th>
            <th>From</th>
            <th>To</th>
            <th>Days</th>
            <th>Total Price</th>
            <th>Plate Number</th>
        
        </tr>
    </thead>
    <tbody>
    @foreach ($reservations as $reservation)
        <tr>
            <td>{{ $reservation->name }}</td>
            <td>{{ $reservation->email }}</td>
            <td>{{ $reservation->address }}</td>
            <td>{{ $reservation->phone }}</td>
            <td>{{ $reservation->date_from }}</td>
            <td>{{ $reservation->date_to }}</td>
            <td>{{ $reservation->days }}</td>
            <td>{{ $reservation->total_price }}</td>
            <td>{{ $reservation->car->plate_number}}</td>
            <td>
                <form action="{{ route('reservation.show', $reservation->id) }}" method="GET" style="display:inline;">
                    <button type="submit">View</button>
                </form>
            
            </td>
            <td>
                <form action="{{ route('reservation.destroy', $reservation->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection
