<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupUserRole extends Model
{
    use HasFactory;

    protected $table = 'group_user_role';
    protected $fillable = [
        'group_id',
        'user_id',
        'role_id',
    ];

    public function group() { return $this->belongsTo(Group::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function role() { return $this->belongsTo(Role::class); }
} 