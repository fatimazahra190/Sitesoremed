<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\ContactReplyMail;
use App\Models\SecurityLog;

class ContactReplyController extends Controller
{
    public function showReplyForm(Contact $contact)
    {
        $this->authorize('reply contacts');
        return view('admin.contacts.reply', compact('contact'));
    }

    public function sendReply(Request $request, Contact $contact)
    {
        $this->authorize('reply contacts');
        $request->validate([
            'reply' => 'required|string|max:2000',
        ]);
        // Envoi email
        Mail::to($contact->email)->send(new ContactReplyMail($contact, $request->reply));
        // Enregistrement
        $contact->reply = $request->reply;
        $contact->replied_at = now();
        $contact->replied_by = Auth::id();
        $contact->save();
        // Journalisation
        SecurityLog::create([
            'user_id' => Auth::id(),
            'action' => 'contact_replied',
            'target_type' => 'contact',
            'target_id' => $contact->id,
            'details' => $request->reply,
            'ip_address' => $request->ip(),
        ]);
        return redirect()->route('admin.contacts.show', $contact)->with('success', 'Réponse envoyée et enregistrée.');
    }
}
