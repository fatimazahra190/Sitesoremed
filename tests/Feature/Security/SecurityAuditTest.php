<?php

use App\Models\User;

it('forbids access to security audit for user without permission', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin/advanced-logs')->assertForbidden();
});

it('allows access to security audit for user with permission', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super admin');
    $this->actingAs($admin)->get('/admin/advanced-logs')->assertOk();
});

it('can export security logs as CSV', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super admin');
    $this->actingAs($admin)->get('/admin/advanced-logs?export=excel')->assertSuccessful();
}); 