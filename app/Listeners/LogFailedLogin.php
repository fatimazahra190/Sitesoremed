<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use App\Models\SecurityLog;

class LogFailedLogin
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        SecurityLog::create([
            'user_id' => $event->user?->id,
            'action' => 'login_failed',
            'ip_address' => request()->ip(),
            'details' => json_encode([
                'email' => request('email'),
                'user_agent' => request()->userAgent(),
            ]),
        ]);
    }
} 