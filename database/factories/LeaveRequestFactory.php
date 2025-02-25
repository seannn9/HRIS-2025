<?php

namespace Database\Factories;

use App\Enums\AttendanceType;
use App\Enums\LeaveStatus;
use App\Enums\LeaveType;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'leave_type' => $this->faker->randomElement(LeaveType::cases()),
            'start_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+2 days', '+1 month'),
            'reason' => $this->faker->paragraph,
            'shift_covered' => $this->faker->randomElements(AttendanceType::values(), 2),
            'status' => $this->faker->randomElement(LeaveStatus::cases()),
            'ticket_number' => $this->faker->unique()->bothify('LEAVE-#####'),
        ];
    }
}
