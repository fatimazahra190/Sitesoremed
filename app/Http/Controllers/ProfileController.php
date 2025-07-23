<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $user->fill($validated);

        // Gestion du consentement RGPD
        if (isset($validated['consent']) && $validated['consent']) {
            if (!$user->consent) {
                $user->consent_accepted_at = now();
            }
            $user->consent = true;
        } else {
            $user->consent = false;
            $user->consent_accepted_at = null;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

         // Anonymisation RGPD
         $user->name = 'Utilisateur supprimé';
         $user->email = 'deleted_' . $user->id . '_' . time() . '@deleted.com';
         $user->password = bcrypt(str()->random(32));
         $user->consent = false;
         $user->consent_accepted_at = null;
         // Ajoute ici d'autres champs à anonymiser si besoin (ex: phone, address...)
         $user->save();
 
         // Révoquer tous les rôles et tokens
         $user->syncRoles([]);
         if (method_exists($user, 'tokens')) {
             $user->tokens()->delete();
         }

         // Journalisation RGPD
         \App\Models\SecurityLog::create([
             'user_id' => $user->id,
             'action' => 'user_deleted',
             'ip_address' => $request->ip(),
             'details' => json_encode(['deleted_by' => auth()->id()]),
         ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

         return Redirect::to('/')->with('status', 'Votre compte a été anonymisé conformément au RGPD.');
    }
}
