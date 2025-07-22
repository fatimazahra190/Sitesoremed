<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return view ('user.index');
    }
    
    public function account_dashboard()
    {
    return view("users.dashboard");
    }
    
    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->delete();
        return redirect('/')->with('success', 'Votre compte a été supprimé avec succès.');
    }

    public function managerDashboard()
    {
        $stats = [
            'news_count' => \App\Models\News::count(),
            'services_count' => \App\Models\Service::count(),
            'contacts_count' => \App\Models\Contact::where('status', 'pending')->count(),
        ];
        $pendingNews = \App\Models\News::where('status', 'pending')->take(5)->get();
        $pendingServices = \App\Models\Service::where('status', 'pending')->take(5)->get();
        return view('dashboard.manager', compact('stats', 'pendingNews', 'pendingServices'));
    }

    public function editorDashboard()
    {
        $editableNews = \App\Models\News::orderBy('updated_at', 'desc')->take(5)->get();
        $editableServices = \App\Models\Service::orderBy('updated_at', 'desc')->take(5)->get();
        $recentEdits = collect(); // À remplacer par un vrai historique si besoin
        return view('dashboard.editor', compact('editableNews', 'editableServices', 'recentEdits'));
    }

    public function viewerDashboard()
    {
        $latestNews = \App\Models\News::orderBy('created_at', 'desc')->take(5)->get();
        $latestServices = \App\Models\Service::orderBy('created_at', 'desc')->take(5)->get();
        $recentContacts = \App\Models\Contact::orderBy('created_at', 'desc')->take(5)->get();
        return view('dashboard.viewer', compact('latestNews', 'latestServices', 'recentContacts'));
    }

    public function userDashboard()
    {
        $myServices = \App\Models\Service::where('user_id', auth()->id())->get();
        return view('dashboard.user', compact('myServices'));
    }
}
