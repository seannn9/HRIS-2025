<?php

use App\Enums\UserRole;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

describe('CheckCreatedAttendance Middleware Test', function () {
    beforeEach(function() {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $this->actingAs($user);
    });

    it('cannot access if there\'s no valid session data', function() {
        $response = $this->get(route('attendance.create.success'));
        $response->assertRedirect(route('attendance.index'));
    });

    it('can access if there\'s a valid session data', function() {
        $message = "Yes!";
        $this->withSession([
            "success" => $message,
            "attendance" => Attendance::factory()->create()
        ]);

        $response = $this->get(route('attendance.create.success'));
        $response->assertSee($message);
        $response->assertStatus(200);
    });
});
