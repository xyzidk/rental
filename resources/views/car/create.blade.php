@extends('layout')

@section('content')
<h2>Create car</h2>

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

<form action="{{ route('car.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <table>
        <th></th>
        <tr>
            <td>Type:</td>
            <td><input type="text" name="type"></td>
        </tr>
        <tr>
            <td>Plate Number:</td>
            <td><input type="text" name="plate_number"></td>
        </tr>
        <tr>
            <td>Rental Price:</td>
            <td><input type="text" name="rental_price"></td>
        </tr>
        <tr>
            <td>Image:</td>
            <td><input type="file" name="image" accept="image/*" required></td>
        </tr>
        <tr>
            <td>Status:</td>
            <td>
                <select name="status">
                    <option value="reserved">Reserved</option>
                    <option value="not_reserved" selected>Not Reserved</option>
                    <option value="deactivated">Deactivated</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Submit">
            </td>
        </tr>
    </table>
    
</form>
<a href="{{ route('car.index') }}" style="display:block; text-align:center; margin: 24px 0 0 0;"> Back</a>

@endsection
