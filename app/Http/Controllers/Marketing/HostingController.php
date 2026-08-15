<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\HostingPackage;

class HostingController extends Controller
{
    public function index()
    {
        return redirect()->route('hosting.shared');
    }

    public function shared()
    {
        $packages = HostingPackage::where('is_active', true)
            ->where('type', 'shared')
            ->orderBy('sort_order')
            ->get();

        return view('marketing.hosting.shared', compact('packages'));
    }

    public function wordpress()
    {
        $packages = HostingPackage::where('is_active', true)
            ->where('type', 'wordpress')
            ->orderBy('sort_order')
            ->get();

        return view('marketing.hosting.wordpress', compact('packages'));
    }

    public function vps()
    {
        $packages = HostingPackage::where('is_active', true)
            ->where('type', 'vps')
            ->orderBy('sort_order')
            ->get();

        return view('marketing.hosting.vps', compact('packages'));
    }

    public function email()
    {
        $packages = HostingPackage::where('is_active', true)
            ->where('type', 'email')
            ->orderBy('sort_order')
            ->get();

        return view('marketing.hosting.email', compact('packages'));
    }
}
