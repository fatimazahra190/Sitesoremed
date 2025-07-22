<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Lockout;
use App\Models\SecurityLog;

class LogLockout
{
    public function handle(Lockout $event)
    {
        SecurityLog::create([
            'user_id' => null,
            'action' => 'login_lockout',
            'ip_address' => request()->ip(),
            'details' => json_encode([
                'email' => request('email'),
                'user_agent' => request()->userAgent(),
            ]),
        ]);
    }
} 