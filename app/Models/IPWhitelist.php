<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPWhitelist extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'description',
        'added_by',
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public static function isWhitelisted($ip)
    {
        return static::where('ip_address', $ip)->exists();
    }
} 