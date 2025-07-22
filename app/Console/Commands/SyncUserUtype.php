<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SyncUserUtype extends Command
{
    protected $signature = 'users:sync-utype';
    protected $description = 'Synchronise le champ utype de tous les utilisateurs avec leur rôle principal (Spatie)';

    public function handle()
    {
        $this->info('Synchronisation des champs utype pour tous les utilisateurs...');
        $count = 0;
        foreach (User::all() as $user) {
            $roles = $user->getRoleNames();
            $utype = $roles->first() ?? null;
            if ($user->utype !== $utype) {
                $user->utype = $utype;
                $user->save();
                $count++;
            }
        }
        $this->info("$count utilisateurs synchronisés.");
        $this->info('Terminé.');
    }
} 