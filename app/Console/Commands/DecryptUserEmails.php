<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class DecryptUserEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:decrypt-user-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Déchiffrement des emails utilisateurs...');

        // Désactive les events pour éviter les problèmes de notifications, etc.
        User::withoutEvents(function () {
            $users = User::all();
            $count = 0;
            foreach ($users as $user) {
                try {
                    // Déchiffrer l'email
                    //$decryptedEmail = decrypt($user->getRawOriginal('email'));
                    //$user->email = $decryptedEmail;
                    // Déchiffrer le nom
                    $decryptedName = decrypt($user->getRawOriginal('name'));
                    $user->name = $decryptedName;
                    $user->save();
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Erreur pour l'utilisateur ID {$user->id}: " . $e->getMessage());
                }
            }
            $this->info("Emails et noms déchiffrés pour $count utilisateurs.");
        });

        $this->info('Terminé.');
    }
}
