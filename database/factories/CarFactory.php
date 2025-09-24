<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Car;

class CarFactory extends Factory
{
    protected $model = Car::class;
   
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1, 1000),
            'type' => $this->faker->randomElement(['sedan', 'suv', 'hatchback', 'coupe', 'convertible']),
            'plate_number' => strtoupper($this->faker->bothify('??###??')),
            'rental_price' => $this->faker->numberBetween(50, 300),
            'image_path' => 'cars/' . $this->faker->image('public/storage/cars', 640, 480, null, false),
            'status' => $this->faker->randomElement(['not_reserved', 'reserved', 'deactivated']),
        ];
    }
}
