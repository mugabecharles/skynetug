<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load([
            'hostingAccounts.hostingPackage',
            'domains',
            'invoices' => fn($q) => $q->where('status', 'unpaid')->orWhere('status', 'overdue'),
            'supportTickets' => fn($q) => $q->whereIn('status', ['open', 'in_progress']),
        ]);

        $activeHosting   = $user->hostingAccounts->where('status', 'active')->count();
        $activeDomains   = $user->domains->where('status', 'active')->count();
        $unpaidInvoices  = $user->invoices->count();
        $openTickets     = $user->supportTickets->count();

        $recentInvoices = Auth::user()->invoices()
            ->with('order')
            ->latest()
            ->take(5)
            ->get();

        $announcements = Announcement::where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'activeHosting',
            'activeDomains',
            'unpaidInvoices',
            'openTickets',
            'recentInvoices',
            'announcements'
        ));
    }
}
