<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'published_at',
    ];
    protected $casts = [
        'published_at' => 'datetime',
    ];
}
