<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

describe('Redirect If Authenticated Middleware', function () {
    beforeEach(function () {
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active'
        ]);
        $this->actingAs($this->user);
    });

    test("authenticated users are redirected to '/'", function () {
        $response = $this->get('/login');
        $response->assertRedirect('/');
    });

    test("unauthenticated users are redirected to '/login'", function () {
        $this->get('/logout');
        $response = $this->get('/');
        $response->assertRedirect('/login');
    });

    test("users accessing '/authenticate' with GET method returns 404", function () {
        $response = $this->get('/authenticate');
        $response->assertStatus(404);
    });
});
