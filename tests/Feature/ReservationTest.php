<?php

use App\Models\Car;
use App\Models\Reservation;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('shows reservations on index page', function () {
    $reservation = Reservation::factory()->create();

    $this->get(route('reservation.index'))
        ->assertStatus(200)
        ->assertViewIs('reservation.index')
        ->assertViewHas('reservations', fn($reservations) => $reservations->contains($reservation));
});

it('calculates total price on create page', function () {
    $car = Car::factory()->create(['rental_price' => 100]);

    $this->get(route('reservation.create', [
        'car_id' => $car->id,
        'date_from' => '2025-01-01',
        'date_to' => '2025-01-03',
    ]))
        ->assertStatus(200)
        ->assertViewHas('total_price', 300);
});

it('creates reservation and marks car as reserved', function () {
    $car = Car::factory()->create(['status' => 'not_reserved', 'rental_price' => 100]);

    $data = [
        'car_id' => $car->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'address' => '123 Street',
        'phone' => '123456789',
        'date_from' => '2025-01-01',
        'date_to' => '2025-01-02',
        'days' => 2,
        'total_price' => 200,
    ];

     $response = $this->post(route('reservation.store'), $data);

    if (env('IS_ADMIN')) {
        $response->assertRedirect('/reservation')->assertSessionHas('success');
    } else {
        $response->assertRedirect('/')->assertSessionHas('success');
    }



    expect(Reservation::where('name', 'John Doe')->exists())->toBeTrue();
    expect(Car::find($car->id)->status)->toBe('reserved');
});

it('shows a reservation', function () {
    $reservation = Reservation::factory()->create();

    $this->get(route('reservation.show', $reservation->id))
        ->assertStatus(200)
        ->assertViewIs('reservation.show')
        ->assertViewHas('reservation', $reservation);
});

it('deletes reservation and resets car status', function () {
    $car = Car::factory()->create(['status' => 'reserved']);
    $reservation = Reservation::factory()->create(['car_id' => $car->id]);

    $this->delete(route('reservation.destroy', $reservation->id))
        ->assertRedirect(route('reservation.index'))
        ->assertSessionHas('success');

    expect(Reservation::find($reservation->id))->toBeNull();
    expect(Car::find($car->id)->status)->toBe('not_reserved');
});
