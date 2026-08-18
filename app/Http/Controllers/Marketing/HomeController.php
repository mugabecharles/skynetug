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
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        // If no featured packages, fall back to first 4 active shared packages
        if ($featuredPackages->isEmpty()) {
            $featuredPackages = HostingPackage::where('is_active', true)
                ->where('type', 'shared')
                ->orderBy('sort_order')
                ->take(4)
                ->get();
        }

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
