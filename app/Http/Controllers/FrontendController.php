<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Service;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function about()
    {
        return view('frontend.about');
    }

    public function productsServices()
    {
        $services = Service::orderBy('type')->get();
        return view('frontend.products-services', compact('services'));
    }

    public function serviceAreas()
    {
        $services = Service::orderBy('area')->get();
        return view('frontend.service-areas', compact('services'));
    }

    public function news()
    {
        $news = News::whereNotNull('published_at')
                   ->where('published_at', '<=', now())
                   ->orderBy('published_at', 'desc')
                   ->paginate(6);
        return view('frontend.news', compact('news'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }
} 