<?php

namespace Database\Factories;

use App\Enums\AttendanceStatus;
use App\Enums\AttendanceType;
use App\Enums\ShiftType;
use App\Enums\WorkMode;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
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
            'date' => $this->faker->dateTimeBetween('-1 year'),
            'shift_type' => $this->faker->randomElement(ShiftType::cases()),
            'type' => $this->faker->randomElement(AttendanceType::cases()),
            'time' => $this->faker->time(),
            'work_mode' => $this->faker->randomElement(WorkMode::cases()),
            'status' => $this->faker->randomElement(AttendanceStatus::cases()),
            'ticket_number' => $this->faker->optional()->bothify('TICKET-#####'),
            'screenshot_workstation_selfie' => $this->faker->optional()->imageUrl(),
            'screenshot_cgc_chat' => $this->faker->optional()->imageUrl(),
            'screenshot_department_chat' => $this->faker->optional()->imageUrl(),
            'screenshot_team_chat' => $this->faker->optional()->imageUrl(),
            'screenshot_group_chat' => $this->faker->optional()->imageUrl(),
        ];
    }
}
