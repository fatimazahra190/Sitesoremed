<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'utype',
        'status',
        'suspended_until',
        'surname',
        'mfa_secret',
        'mfa_enabled',
        'password_changed_at',
        'force_password_change',
        'last_login_at',
        'login_attempts',
        'locked_until',
        'consent', // Ajouté pour RGPD
        'consent_accepted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_until' => 'datetime',
            'password_changed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'mfa_enabled' => 'boolean',
            'force_password_change' => 'boolean',
            'consent' => 'boolean',
            'consent_accepted_at' => 'datetime',
            'deleted_at' => 'datetime',
            // 'email' => 'encrypted',
            // 'phone' => 'encrypted', // décommente si tu as un champ phone
        ];
    }

    /**
     * Get the user's full name
     */
    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }

    /**
     * Check if user is suspended
     */
    public function isSuspended()
    {
        return $this->suspended_until && $this->suspended_until->isFuture();
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function resetUserPassword(Request $request, User $user)
    {
        // ... logique de reset du mot de passe ...

        SecurityLog::create([
            'user_id' => $user->id,
            'action' => 'reset_password',
            'performed_by' => auth()->id(),
            'ip_address' => $request->ip(),
            'details' => null,
        ]);

        // ... retour de la réponse ...
    }

    public function permanentlyDeleteUser(Request $request, User $user)
    {
        // Anonymisation des données personnelles
        $user->name = 'Anonyme';
        $user->email = 'deleted_' . $user->id . '@example.com';
        $user->consent = false;
        // Ajoute ici d’autres champs à anonymiser si besoin, ex :
        // $user->phone = null;
        // $user->address = null;
        $user->save();

        // Suppression définitive (hard delete)
        // Si tu utilises SoftDeletes, il faut forcer la suppression :
        $user->forceDelete();

        // Journalisation de l’action
        SecurityLog::create([
            'user_id' => $user->id,
            'action' => 'permanent_delete',
            'performed_by' => auth()->id(),
            'ip_address' => $request->ip(),
            'details' => null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé et anonymisé.');
    }

    public function securityChecklist()
    {
        $users = User::all()->map(function($user) {
            return [
                'user' => $user,
                'mfa_enabled' => $user->mfa_enabled ?? false,
                'password_age_days' => $user->password_changed_at
                    ? Carbon::parse($user->password_changed_at)->diffInDays(now())
                    : null,
                'consent' => $user->consent ?? false,
                'status' => $user->status ?? null,
            ];
        });

        return view('admin.security-checklist', compact('users'));
    }
}
