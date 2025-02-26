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
        $request = new Request();
        $request->session()->put("success", "Yes!");
        $request->session()->put("attendance", Attendance::factory()->create());

        $response = $this->get(route('attendance.create.success'), $request);
        $response->assertRedirect(route('attendance.index'));
    });
});
