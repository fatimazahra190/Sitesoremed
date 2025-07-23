<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\SoftDeletes;


class Role extends SpatieRole
{
    // Exemple : ajouter des champs personnalisés
    protected $fillable = [
        'name', 
        'description', ];

    
}
