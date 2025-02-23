<?php

use App\Models\User;

test("authenticated users are redirected to '/'", function () {
    $email = 'admin@example.com';
    $user = User::whereEmail($email)->first();

    $employee = $user->employee()->first();

    $this->post('/authenticate', [
        'employee-number' => $employee->id,
        'remember-me'     => 'on',
        'email'           => $email,
        'password'        => 'password',
    ]);

    $response = $this->get('/login');
    $response->assertRedirect('/');
});

test("unauthenticated users are redirected to '/login'", function () {
    $response = $this->get('/');
    $response->assertRedirect('/login');
});

test("users accessing '/authenticate' with GET method returns 404", function () {
    $response = $this->get('/authenticate');
    $response->assertStatus(404);
});
