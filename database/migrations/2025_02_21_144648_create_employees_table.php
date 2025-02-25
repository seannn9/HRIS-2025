<?php

use App\Enums\Department;
use App\Enums\EmployeeStatus;
use App\Enums\EmploymentType;
use App\Enums\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->date('birthdate');
            $table->enum('gender', Gender::values());
            $table->string('contact_number', 20);
            $table->text('address');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_number', 20);
            $table->date('hire_date');
            $table->enum('employment_type', EmploymentType::values());
            $table->enum('department', Department::values());
            $table->string('position')->nullable();
            $table->enum('status', EmployeeStatus::values())->default(EmployeeStatus::ACTIVE->value);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
