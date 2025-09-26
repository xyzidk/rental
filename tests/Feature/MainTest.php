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

it('excludes deactivated cars', function () {
    $car = Car::factory()->create(['status' => 'deactivated']);

    $response = $this->post(route('car.listCars'), [
        'date_from' => '2025-01-01',
        'date_to' => '2025-01-05',
    ]);

    $response->assertStatus(200);
    $response->assertViewHas('cars', function ($cars) use ($car) {
        expect($cars->contains($car))->toBeFalse();

        return true;
    });
});

it('includes cars if reservation does not overlap the given period', function () {
    $car = Car::factory()->create(['status' => 'reserved']);

    Reservation::factory()->create([
        'car_id' => $car->id,
        'date_from' => '2025-01-01',
        'date_to' => '2025-01-02',
    ]);

    $response = $this->post(route('car.listCars'), [
        'date_from' => '2025-01-05',
        'date_to' => '2025-01-07',
    ]);

    $response->assertStatus(200);
    $response->assertViewHas('cars', function ($cars) use ($car) {
        expect($cars->contains($car))->toBeTrue();

        return true;
    });
});
