<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calendar>
 */
class CalendarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'visitor_name' => fake()->name(),
            'visitor_mobile' => fake()->phoneNumber(),
            'visitor_email' => fake()->safeEmail(),
            'vehicle_number' => fake()->bothify('########'),
            'vehicle_color' => fake()->colorName(),
            'vehicle_model' => fake()->word(),
            'has_arrival_booking' => $this->faker->randomElement(['Yes', 'No']),
            'arrival_mode' => function (array $attributes) {
                return $attributes['has_arrival_booking'] === 'Yes'
                    ? $this->faker->randomElement(['Flight', 'Car', 'Train', 'Bus'])
                    : null;
            },
            'arrival_datetime' => function (array $attributes) {
                return $attributes['has_arrival_booking'] === 'Yes'
                    ? $this->faker->dateTimeBetween('now', '+1 month')
                    : null;
            },
            'has_hold_luggage' => function (array $attributes) {
                return $attributes['has_arrival_booking'] === 'Yes'
                    ? $this->faker->randomElement(['Yes', 'No'])
                    : null;
            },
            'has_departure_booking' => $this->faker->randomElement(['Yes', 'No']),
            'departure_meeting_time' => function (array $attributes) {
                return $attributes['has_departure_booking'] === 'Yes'
                    ? $this->faker->time()
                    : null;
            },
            'additional_info' => $this->faker->optional()->paragraph(),
        ];
    }
}
