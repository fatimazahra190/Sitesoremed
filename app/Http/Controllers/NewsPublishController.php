<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SecurityLog;

class NewsPublishController extends Controller
{
    public function publish(News $news)
    {
        $this->authorize('publish news');
        $news->published = true;
        $news->published_at = now();
        $news->save();
        SecurityLog::create([
            'user_id' => Auth::id(),
            'action' => 'news_published',
            'target_type' => 'news',
            'target_id' => $news->id,
            'details' => 'News published',
            'ip_address' => request()->ip(),
        ]);
        return back()->with('success', 'News published.');
    }
    public function unpublish(News $news)
    {
        $this->authorize('publish news');
        $news->published = false;
        $news->published_at = null;
        $news->save();
        SecurityLog::create([
            'user_id' => Auth::id(),
            'action' => 'news_unpublished',
            'target_type' => 'news',
            'target_id' => $news->id,
            'details' => 'News unpublished',
            'ip_address' => request()->ip(),
        ]);
        return back()->with('success', 'News unpublished.');
    }
}
