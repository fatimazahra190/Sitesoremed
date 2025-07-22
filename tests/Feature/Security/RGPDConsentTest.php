<?php

test('registration fails without consent', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test-consent@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        // pas de consent
    ]);
    $response->assertSessionHasErrors('consent');
});

test('registration stores consent_accepted_at', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test-consent2@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'consent' => 'on',
    ]);
    $user = \App\Models\User::where('email', 'test-consent2@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->consent_accepted_at)->not->toBeNull();
}); 