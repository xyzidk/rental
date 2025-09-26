<?php

use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('shows car index page', function () {
    $cars = Car::factory()->count(3)->create();

    $this->get(route('car.index'))
        ->assertStatus(200)
        ->assertSee($cars->first()->type);
});

it('creates a new car', function () {
    $data = [
        'type' => 'sedan',
        'plate_number' => 'ABC123',
        'rental_price' => 150,
        'status' => 'not_reserved',
        'image' => UploadedFile::fake()->create('car.jpg', 100, 'image/jpeg'),
    ];

    $this->post(route('car.store'), $data)
        ->assertRedirect(route('car.index'))
        ->assertSessionHas('success');

    expect(Car::where('plate_number', 'ABC123')->exists())->toBeTrue();
});

it('updates a car', function () {
    $car = Car::factory()->create([
        'type' => 'suv',
        'plate_number' => 'XYZ789',
        'rental_price' => '200',
        'status' => 'not_reserved',
    ]);

    $updateData = [
        'type' => 'convertible',
        'plate_number' => 'XYZ7891',
        'rental_price' => '250',
        'status' => 'reserved',
    ];

    $this->put(route('car.update', $car->id), $updateData)
        ->assertRedirect(route('car.index'))
        ->assertSessionHas('success');

    $car->refresh();
    expect($car->type)->toBe('convertible');
    expect($car->rental_price)->toBe('250');
    expect($car->plate_number)->toBe('XYZ7891');
    expect($car->status)->toBe('reserved');
});

it('deactivates a car', function () {
    $car = Car::factory()->create(['status' => 'not_reserved']);

    $this->patch(route('car.deactivate', $car->id))
        ->assertRedirect(route('car.index'))
        ->assertSessionHas('success');

    expect(Car::find($car->id)->status)->toBe('deactivated');
});

it('cannot delete a reserved car', function () {
    $car = Car::factory()->create(['status' => 'reserved']);

    $this->delete(route('car.destroy', $car->id))
        ->assertRedirect(route('car.index'))
        ->assertSessionHas('error');

    expect(Car::find($car->id))->not->toBeNull();
});

it('deletes a non-reserved car', function () {
    Storage::fake('public');

    $car = Car::factory()->create([
        'status' => 'not_reserved',
        'image_path' => 'cars/test.jpg',
    ]);

    Storage::disk('public')->put('cars/test.jpg', 'dummy');

    $this->delete(route('car.destroy', $car->id))
        ->assertRedirect(route('car.index'))
        ->assertSessionHas('success');

    expect(Car::find($car->id))->toBeNull();

    expect(Storage::disk('public')->exists('cars/test.jpg'))->toBeFalse();
});

it('shows car details in show view', function () {
    $car = Car::factory()->create([
        'type' => 'sedan',
        'plate_number' => 'ABC123',
        'rental_price' => 150,
        'status' => 'not_reserved',
    ]);

    $this->get(route('car.show', $car->id))
        ->assertStatus(200)
        ->assertSee($car->type)
        ->assertSee($car->plate_number)
        ->assertSee((string) $car->rental_price)
        ->assertSee('Not Reserved');
});

it('shows car details in edit view', function () {
    $car = Car::factory()->create([
        'type' => 'suv',
        'plate_number' => 'XYZ789',
        'rental_price' => 200,
        'status' => 'not_reserved',
    ]);

    $this->get(route('car.edit', $car->id))
        ->assertStatus(200)
        ->assertSee($car->type)
        ->assertSee($car->plate_number)
        ->assertSee((string) $car->rental_price)
        ->assertSee($car->status);
});
