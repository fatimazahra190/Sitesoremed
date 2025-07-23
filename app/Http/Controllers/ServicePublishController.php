<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SecurityLog;

class ServicePublishController extends Controller
{
    public function publish(Service $service)
    {
        $this->authorize('publish services');
        $service->published = true;
        $service->published_at = now();
        $service->save();
        SecurityLog::create([
            'user_id' => Auth::id(),
            'action' => 'service_published',
            'target_type' => 'service',
            'target_id' => $service->id,
            'details' => 'Service published',
            'ip_address' => request()->ip(),
        ]);
        return back()->with('success', 'Service published.');
    }
    public function unpublish(Service $service)
    {
        $this->authorize('publish services');
        $service->published = false;
        $service->published_at = null;
        $service->save();
        SecurityLog::create([
            'user_id' => Auth::id(),
            'action' => 'service_unpublished',
            'target_type' => 'service',
            'target_id' => $service->id,
            'details' => 'Service unpublished',
            'ip_address' => request()->ip(),
        ]);
        return back()->with('success', 'Service unpublished.');
    }
}
