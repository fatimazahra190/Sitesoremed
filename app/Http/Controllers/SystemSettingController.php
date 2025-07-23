<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Gate;

class SystemSettingController extends Controller
{
    public function index()
    {
        Gate::authorize('manage system settings');
        $settings = SystemSetting::all();
        return view('admin.system-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        Gate::authorize('manage system settings');
        foreach ($request->settings as $key => $value) {
            SystemSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return redirect()->route('admin.system-settings.index')->with('success', 'Paramètres mis à jour.');
    }
}
