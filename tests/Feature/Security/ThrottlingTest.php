<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

it('blocks login after 5 failed attempts (throttling)', function () {
    $user = User::factory()->create();
    $ip = '127.0.0.1';
    for ($i = 0; $i < 5; $i++) {
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ], ['REMOTE_ADDR' => $ip]);
    }
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ], ['REMOTE_ADDR' => $ip]);
    $response->assertSessionHasErrors();
    $response->assertStatus(429);
});

it('logs failed login attempts in security_logs', function () {
    $user = User::factory()->create();
    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);
    $log = DB::table('security_logs')->where('action', 'failed_login')->where('user_id', $user->id)->first();
    expect($log)->not->toBeNull();
}); 