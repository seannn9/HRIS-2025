<?php

namespace Database\Factories;

use App\Enums\EmploymentStatus;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            // 'employee_id' => 'EMP' . Str::padLeft(fake()->unique()->randomNumber(5), 5, '0'),
            'birthdate' => fake()->optional()->date(),
            'gender' => fake()->optional()->randomElement(Gender::values()),
            'contact_number' => fake()->optional()->phoneNumber(),
            'address' => fake()->optional()->address(),
            'emergency_contact_name' => fake()->optional()->name(),
            'emergency_contact_number' => fake()->optional()->phoneNumber(),
            'hire_date' => fake()->date(),
            'employment_status' => fake()->randomElement(EmploymentStatus::values()),
        ];
    }
}
