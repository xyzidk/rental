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

@if(session('error'))
    <div style="color: red;">
        {{ session('error') }}
    </div>
@endif

<table>
    <thead>
        <tr>
            <th>Type</th>
            <th>Plate number</th>
            <th>Status</th>
            <th>Image</th>
        
        </tr>
    </thead>
    <tbody>
    @foreach ($cars as $car)
        <tr>
            <td>{{ $car->type }}</td>
            <td>{{ $car->plate_number }}</td>
            <td>
            {{ ['reserved' => 'Reserved',
            'deactivated' => 'Deactivated',
            'not_reserved' => 'Not Reserved'
            ][$car->status] ?? 'Unknown' }}
            </td>
            <td><img src="{{ Storage::url($car->image_path) }}" alt="Car" width="50" height="50"></td>
            <td>
                <form action="{{ route('car.show', $car->id) }}" method="GET" style="display:inline;">
                    <button type="submit">View</button>
                </form>
            
                <form action="{{ route('car.edit', $car->id) }}" method="GET" style="display:inline;">
                    <button type="submit">Edit</button>
                </form>
                
                <form action="{{ route('car.destroy', $car->id) }}" method="POST" style="display:inline;">
            @csrf
    @method('DELETE')
    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
            @if($car->status !== 'deactivated')
        <form action="{{ route('car.deactivate', $car->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('PATCH')
            <button type="submit" class="deactivate-btn">Deactivate</button>
        </form>
        @endif
            </td>
        </tr>
        </td>

    @endforeach
    </tbody>
</table>

<a href="{{ route('car.create') }}" style="margin-left: 6rem;">Create Car</a>

@endsection
