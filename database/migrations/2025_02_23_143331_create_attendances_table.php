<?php

use App\Enums\AttendanceStatus;
use App\Enums\AttendanceType;
use App\Enums\ShiftType;
use App\Enums\WorkMode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->enum('shift_type', ShiftType::values());
            $table->enum('type', AttendanceType::values());
            $table->dateTime('time');
            $table->enum('work_mode', WorkMode::values())->default(WorkMode::ONSITE->value);
            $table->enum('status', AttendanceStatus::values())->default(AttendanceStatus::PRESENT->value);
            $table->string('ticket_number')->unique()->nullable();
            $table->string('screenshot_workstation_selfie_path')->nullable();
            $table->string('screenshot_cgc_chat')->nullable();
            $table->string('screenshot_department_chat')->nullable();
            $table->string('screenshot_team_chat')->nullable();
            $table->string('screenshot_group_chat')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};