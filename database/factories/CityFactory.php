<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->city(),
            'logo' => 'https://res.cloudinary.com/dkusd5mgf/image/upload/v1686958467/tin4tqaddqry7obpkjjc.jpg',  
            'user_id' => '1',          
        ];
    }
}
