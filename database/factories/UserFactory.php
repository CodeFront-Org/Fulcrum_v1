<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->lastName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'id_number' => $this->faker->unique()->randomNumber(8),
            'contacts' => $this->faker->phoneNumber,
            'role_type' => $this->faker->numberBetween(1, 3),
            'status' => $this->faker->randomElement([0, 1]),
            'auth_type' => 'email',
            'path' => $this->faker->imageUrl(),
            'last_login' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'login_attempts' => $this->faker->numberBetween(0, 5),
            'blacklist' => $this->faker->randomElement([0, 1]),
            'blacklist_attempts' => $this->faker->numberBetween(0, 3),
            'time_blacklisted' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'otp' => $this->faker->randomNumber(6),
            'otp_expiry' => $this->faker->dateTimeBetween('now', '+1 week'),
            'is_verified' => $this->faker->randomElement([0, 1]),
            'reset_code' => $this->faker->sha1,
            'reset_expiry' => $this->faker->dateTimeBetween('now', '+1 week'),
            'email_verified_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'password' => bcrypt('password'), // default password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
