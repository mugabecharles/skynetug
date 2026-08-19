<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\HostingPackage;
use App\Models\Announcement;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPackages = HostingPackage::where('is_active', true)
            ->where('type', 'shared')
            ->orderBy('sort_order')
            ->get();

        $announcements = Announcement::where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('marketing.home', compact('featuredPackages', 'announcements'));
    }

    public function about()
    {
        return view('marketing.about');
    }
}
