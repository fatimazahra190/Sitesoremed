<?php

use App\Models\User;

it('forbids access to admin for user without permission', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin')->assertForbidden();
});

it('allows access to admin for user with permission', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin)->get('/admin')->assertOk();
}); 