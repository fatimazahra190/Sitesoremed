<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Affiche la page de confirmation de suppression de compte.
     */
    public function showDeleteForm()
    {
        return view('account.delete');
    }

    /**
     * Supprime le compte de l'utilisateur connecté.
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirm' => ['required', 'in:SUPPRIMER'],
        ], [
            'confirm.in' => 'Vous devez taper SUPPRIMER pour confirmer la suppression définitive.'
        ]);
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('status', 'Votre compte a été supprimé avec succès.');
    }
} 