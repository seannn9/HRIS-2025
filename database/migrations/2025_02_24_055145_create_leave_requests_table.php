<?php

use App\Enums\LeaveStatus;
use App\Enums\LeaveType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('leave_type', LeaveType::values());
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason')->nullable();
            $table->json('shift_covered')->nullable();
            $table->enum('status', LeaveStatus::values())->default(LeaveStatus::PENDING->value);
            $table->string('ticket_number')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
