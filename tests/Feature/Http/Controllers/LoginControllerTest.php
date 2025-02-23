<?php

use App\Models\User;

test('logging in with incorrect details returns errors', function () {
    // Send a POST request with credentials that won't match any user.
    $response = $this->post('/authenticate', [
        'employee-number' => 999, // an employee number that won't match
        'remember-me'     => 'nonexistent@example.com', // used as email lookup
        'email'           => 'nonexistent@example.com', // for validation
        'password'        => 'wrongpassword',
    ]);

    // Assert that the session contains an error on the "email" key.
    $response->assertInvalid(['email']);
});

test('logging in with correct details returns success', function () {
    $email = 'admin@example.com';
    // Create a user with a known email and password.
    $user = User::whereEmail($email)->first();
    // Create an employee instance. We assume you have an Employee factory/model.
    $employee = $user->employee()->first();

    // Prepare the POST request data.
    // Note: We pass the user's email in the "remember-me" field because your controller
    // uses that to retrieve the email, and we pass the employee id in "employee-number".
    $response = $this->post('/authenticate', [
        'employee-number' => $employee->id,
        'remember-me'     => 'on',  // used as email for lookup
        'email'           => $email,  // used for validation
        'password'        => 'password',          // plain text for authentication
    ]);

    // Assert that the response redirects to the intended URL ("/")
    $response->assertRedirect('/');
    $response->assertValid(['email']);

    // Assert that the user is authenticated.
    $this->assertAuthenticated();
    $this->assertAuthenticatedAs($user);
});

test('logging in with incorrect employee number returns errors', function () {
    $email = 'admin@example.com';

    $response = $this->post('/authenticate', [
        'employee-number' => -1,
        'remember-me'     => 'on',  // used as email for lookup
        'email'           => $email,  // used for validation
        'password'        => 'password',          // plain text for authentication
    ]);

    $response->assertInvalid(['email']);
});
