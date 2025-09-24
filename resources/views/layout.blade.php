<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    </head>
<body>
   
        <header>
        <nav>
            @if (env('IS_ADMIN'))
            <a href="{{ route('car.index') }}">Car</a>
            <a href="{{ route('reservation.index') }}">Reservation</a>
            @endif
        </nav>
    </header>
            

    <main>
        @yield('content')
    </main>
    <footer></footer>
        
</body>
</html>
