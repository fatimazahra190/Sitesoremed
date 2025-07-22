<?php

namespace App\Providers;

use Spatie\Permission\Models\Role;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
        \Illuminate\Support\Facades\Route::model('role', Role::class);
    }
}