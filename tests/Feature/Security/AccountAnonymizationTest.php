<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

it('anonymizes user data on account deletion', function () {
    $user = User::factory()->create([
        'name' => 'To Be Deleted',
        'email' => 'delete-me@example.com',
        'consent' => true,
        'consent_accepted_at' => now(),
    ]);
    $this->actingAs($user)->delete('/profile', [
        'password' => 'password',
    ]);
    $deleted = User::withTrashed()->where('email', 'delete-me@example.com')->first();
    expect($deleted)->not->toBeNull();
    expect($deleted->name)->toBe('Utilisateur supprimé');
    expect($deleted->email)->not->toBe('delete-me@example.com');
    expect($deleted->consent)->toBeNull();
    expect($deleted->consent_accepted_at)->toBeNull();
});

it('logs account deletion in security_logs', function () {
    $user = User::factory()->create([
        'email' => 'delete-log@example.com',
    ]);
    $this->actingAs($user)->delete('/profile', [
        'password' => 'password',
    ]);
    $log = DB::table('security_logs')->where('action', 'user_deleted')->where('user_id', $user->id)->first();
    expect($log)->not->toBeNull();
}); 