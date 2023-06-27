<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'shortDescription' => $this->faker->paragraph,
            'longDescription' => $this->faker->text,
            'mode' => 'Mode 1',
            'startDate' => $this->faker->date,
            'endDate' => $this->faker->date,
            'startTime' => $this->faker->time('H:i'),
            'endTime' => $this->faker->time('H:i'),
            'image' => $this->faker->imageUrl(),
            'user_id' => User::factory(),
            'max_participants' => $this->faker->randomNumber(),
        ];
    }
}
