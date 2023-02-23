<?php

namespace Database\Factories\Phone;

use Gogain\LaravelPhoneVerification\Models\Phone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PhoneFactory extends Factory
{
    protected $model = Phone::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => fake()->numberBetween(1,999),
            'phone' => fake()->numerify('##########'),
            'code' => fake()->numberBetween(1000,9999),
            'status' => false, 
        ];
    }
}

