<?php

use App\Models\Car;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('requires date_from and date_to', function () {
    $response = $this->post(route('car.listCars'), []);
    $response->assertSessionHasErrors(['date_from', 'date_to']);
});

it('requires date_to to be after or equal date_from', function () {
    $response = $this->post(route('car.listCars'), [
        'date_from' => '2025-01-10',
        'date_to' => '2025-01-05',
    ]);
    $response->assertSessionHasErrors(['date_to']);
});

it('shows only not reserved cars', function () {

    $notreservedCar = Car::factory()->create(['status' => 'not_reserved']);
    $reservedCar = Car::factory()->create(['status' => 'reserved']);
    $deactivatedCar = Car::factory()->create(['status' => 'deactivated']);

    Reservation::factory()->create([
        'car_id' => $reservedCar->id,
        'date_from' => '2025-01-01',
        'date_to' => '2025-01-10',
    ]);

    $response = $this->post(route('car.listCars'), [
        'date_from' => '2025-01-05',
        'date_to' => '2025-01-07',
    ]);

    $response->assertStatus(200);
    $response->assertViewHas('cars', function ($cars) use ($notreservedCar, $reservedCar, $deactivatedCar) {
        expect($cars->contains($notreservedCar))->toBeTrue();
        expect($cars->contains($reservedCar))->toBeFalse();
        expect($cars->contains($deactivatedCar))->toBeFalse();
        return true;
    });

    $response->assertViewHas('date_from', '2025-01-05');
    $response->assertViewHas('date_to', '2025-01-07');
});
