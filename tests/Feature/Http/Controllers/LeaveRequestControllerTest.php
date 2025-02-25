<?php

use App\Models\LeaveRequest;
use App\Enums\{LeaveStatus, LeaveType, UserRole};
use App\Models\Employee;
use App\Models\User;

describe('Leave Request Controller as an Employee', function () {
    beforeEach(function() {
        $user = User::factory()->create(['role' => UserRole::EMPLOYEE]);
        $this->actingAs($user);
        $this->employee = Employee::factory()->create(['user_id' => $user->id]);
    });

    it('can create leave request', function () {
        $response = $this->postJson('/api/leave', [
            'employee_id' => $this->employee->id,
            'leave_type' => LeaveType::SICK->value,
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addDays(3)->format('Y-m-d'),
            'reason' => 'Medical leave',
            'shift_covered' => ['Morning', 'Afternoon']
        ]);
    
        $response->assertCreated();
    });

    it('cannot update approved leave request', function () {
        $leave = LeaveRequest::factory()->create([
            'status' => LeaveStatus::APPROVED,
            'employee_id' => $this->employee->id
        ]);
    
        $response = $this->putJson("/api/leave/{$leave->id}", [
            'reason' => 'Updated reason'
        ]);
    
        $response->assertForbidden();
    });

    it('cannot update leave request status using patch method only', function () {
        $leave = LeaveRequest::factory()->create([
            'status' => LeaveStatus::PENDING,
            'employee_id' => $this->employee->id
        ]);
    
        $response = $this->patchJson("/api/leave/{$leave->id}", [
            'status' => LeaveStatus::APPROVED->value,
        ]);
    
        $response->assertForbidden();
    });

    it('cannot update leave request status using \'/status\' endpoint', function () {
        $leave = LeaveRequest::factory()->create([
            'status' => LeaveStatus::PENDING,
            'employee_id' => $this->employee->id
        ]);
    
        $response = $this->patchJson("/api/leave/{$leave->id}/status", [
            'status' => LeaveStatus::APPROVED->value,
        ]);
    
        $response->assertForbidden();
    });

    it('cannot update approved leave', function () {
        $leave = LeaveRequest::factory()->create(['status' => LeaveStatus::APPROVED]);
    
        $response = $this->patchJson("/api/leave/{$leave->id}", [
            'reason' => 'Updated reason'
        ]);
    
        $response->assertForbidden();
    });
});

describe('Leave Request Controller as Admin', function () {
    beforeEach(function() {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $this->actingAs($user);
        $this->employee = Employee::factory()->create(['user_id' => $user->id]);
    });

    it('can create leave request', function () {
        $response = $this->postJson('/api/leave', [
            'employee_id' => $this->employee->id,
            'leave_type' => LeaveType::SICK->value,
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addDays(3)->format('Y-m-d'),
            'reason' => 'Medical leave',
            'shift_covered' => ['Morning', 'Afternoon']
        ]);
    
        $response->assertCreated();
    });
    
    it('can filter leave requests', function () {
        LeaveRequest::factory()->create(['leave_type' => LeaveType::SICK]);
        LeaveRequest::factory()->create(['leave_type' => LeaveType::VACATION]);
    
        $response = $this->getJson('/api/leave?leave_type=sick');
        
        $response->assertOk()
            ->assertJsonCount(1, 'data');
    });

    it('can update approved leave', function () {
        $leave = LeaveRequest::factory()->create(['status' => LeaveStatus::APPROVED]);
    
        $response = $this->patchJson("/api/leave/{$leave->id}", [
            'reason' => 'Updated reason'
        ]);
    
        $response->assertOk();
    });
});

describe('Leave Request Controller as HR', function () {
    beforeEach(function() {
        $user = User::factory()->create(['role' => UserRole::HR]);
        $this->actingAs($user);
        $this->employee = Employee::factory()->create(['user_id' => $user->id]);
    });

    it('can create leave request', function () {
        $response = $this->postJson('/api/leave', [
            'employee_id' => $this->employee->id,
            'leave_type' => LeaveType::SICK->value,
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addDays(3)->format('Y-m-d'),
            'reason' => 'Medical leave',
            'shift_covered' => ['Morning', 'Afternoon']
        ]);
    
        $response->assertCreated();
    });
    
    it('can filter leave requests', function () {
        LeaveRequest::factory()->create(['leave_type' => LeaveType::SICK]);
        LeaveRequest::factory()->create(['leave_type' => LeaveType::VACATION]);
    
        $response = $this->getJson('/api/leave?leave_type=sick');
        
        $response->assertOk()
            ->assertJsonCount(1, 'data');
    });

    it('can update approved leave', function () {
        $leave = LeaveRequest::factory()->create(['status' => LeaveStatus::APPROVED]);
    
        $response = $this->patchJson("/api/leave/{$leave->id}", [
            'reason' => 'Updated reason'
        ]);
    
        $response->assertOk();
    });
});
