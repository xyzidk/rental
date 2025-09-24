<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reservation;
use App\Models\Car;
use Illuminate\Support\Str;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        $car = Car::factory()->create();

        $dateFrom = $this->faker->dateTimeBetween('-10 week', '+10 week');
        $days = $this->faker->numberBetween(1, 100);
        $dateTo = (clone $dateFrom)->modify("+{$days} days");

        return [
            'car_id' => $car->id,
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'date_from' => $dateFrom->format('Y-m-d'),
            'date_to' => $dateTo->format('Y-m-d'),
            'days' => $days,
            'total_price' => $car->rental_price * $days,
        ];
    }
}
