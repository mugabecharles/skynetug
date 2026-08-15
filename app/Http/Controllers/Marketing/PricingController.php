<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\HostingPackage;

class PricingController extends Controller
{
    public function index()
    {
        $sharedPackages = HostingPackage::where('is_active', true)
            ->where('type', 'shared')
            ->orderBy('sort_order')
            ->get();

        $wordpressPackages = HostingPackage::where('is_active', true)
            ->where('type', 'wordpress')
            ->orderBy('sort_order')
            ->get();

        return view('marketing.pricing', compact('sharedPackages', 'wordpressPackages'));
    }

    public function ssl()
    {
        $sslPackages = HostingPackage::where('is_active', true)
            ->where('type', 'ssl')
            ->orderBy('sort_order')
            ->get();

        return view('marketing.ssl', compact('sslPackages'));
    }

    public function reseller()
    {
        return view('marketing.reseller');
    }

    public function design()
    {
        $designPackages = HostingPackage::where('is_active', true)
            ->where('type', 'design')
            ->orderBy('sort_order')
            ->get();

        return view('marketing.design', compact('designPackages'));
    }
}
