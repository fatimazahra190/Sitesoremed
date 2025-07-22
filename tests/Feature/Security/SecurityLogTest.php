<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

it('logs failed login in security_logs', function () {
    $user = User::factory()->create();
    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);
    $log = DB::table('security_logs')->where('action', 'failed_login')->where('user_id', $user->id)->first();
    expect($log)->not->toBeNull();
});

it('logs role assignment in security_logs', function () {
    $admin = User::factory()->create();
    $user = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin)->post('/admin/users/assign-role', [
        'user_id' => $user->id,
        'role' => 'manager',
    ]);
    $log = DB::table('security_logs')->where('action', 'role_assigned')->where('user_id', $user->id)->first();
    expect($log)->not->toBeNull();
}); 