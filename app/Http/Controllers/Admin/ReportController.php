<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\HostingAccount;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to',   now()->toDateString());

        $revenue     = Payment::where('status', 'completed')->whereBetween('paid_at', [$from, $to])->sum('amount');
        $orderCount  = Order::whereBetween('created_at', [$from, $to])->count();
        $byGateway   = Payment::where('status', 'completed')
            ->whereBetween('paid_at', [$from, $to])
            ->select('gateway', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('gateway')->get();

        $byMonth = Payment::where('status', 'completed')
            ->where('paid_at', '>=', now()->subMonths(12))
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')->orderBy('month')->get();

        return view('admin.reports.sales', compact('revenue', 'orderCount', 'byGateway', 'byMonth', 'from', 'to'));
    }

    public function payments(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to',   now()->toDateString());

        $payments = Payment::with('user')
            ->whereBetween('created_at', [$from, $to])
            ->latest()->paginate(30)->withQueryString();

        $summary = Payment::whereBetween('created_at', [$from, $to])
            ->select('gateway', 'status', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('gateway', 'status')->get();

        return view('admin.reports.payments', compact('payments', 'summary', 'from', 'to'));
    }

    public function customers(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to',   now()->toDateString());

        $new     = User::where('role', 'customer')->whereBetween('created_at', [$from, $to])->count();
        $total   = User::where('role', 'customer')->count();
        $byMonth = User::where('role', 'customer')
            ->where('created_at', '>=', now()->subMonths(12))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')->orderBy('month')->get();

        return view('admin.reports.customers', compact('new', 'total', 'byMonth', 'from', 'to'));
    }

    public function hosting(Request $request)
    {
        $accounts = HostingAccount::with(['hostingPackage', 'server'])
            ->select('status', 'hosting_package_id', 'server_id', DB::raw('COUNT(*) as count'))
            ->groupBy('status', 'hosting_package_id', 'server_id')
            ->get();

        $total     = HostingAccount::count();
        $active    = HostingAccount::where('status', 'active')->count();
        $suspended = HostingAccount::where('status', 'suspended')->count();

        return view('admin.reports.hosting', compact('accounts', 'total', 'active', 'suspended'));
    }

    public function domains(Request $request)
    {
        $byTld = Domain::select('tld', DB::raw('COUNT(*) as count'))
            ->groupBy('tld')->orderByDesc('count')->get();

        $expiringSoon = Domain::where('status', 'active')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->orderBy('expiry_date')->paginate(20);

        $total  = Domain::count();
        $active = Domain::where('status', 'active')->count();

        return view('admin.reports.domains', compact('byTld', 'expiringSoon', 'total', 'active'));
    }

    public function support(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to',   now()->toDateString());

        $total    = SupportTicket::whereBetween('created_at', [$from, $to])->count();
        $open     = SupportTicket::whereIn('status', ['open','in_progress'])->count();
        $resolved = SupportTicket::whereBetween('created_at', [$from, $to])->where('status', 'resolved')->count();

        $byCategory = SupportTicket::whereBetween('created_at', [$from, $to])
            ->select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')->get();

        $byPriority = SupportTicket::whereBetween('created_at', [$from, $to])
            ->select('priority', DB::raw('COUNT(*) as count'))
            ->groupBy('priority')->get();

        return view('admin.reports.support', compact('total', 'open', 'resolved', 'byCategory', 'byPriority', 'from', 'to'));
    }

    public function affiliates()
    {
        $affiliates = Affiliate::with(['user', 'referrals', 'payouts'])->paginate(20);
        $totalCommissions = \App\Models\AffiliateReferral::where('status', 'approved')->sum('commission');

        return view('admin.reports.affiliates', compact('affiliates', 'totalCommissions'));
    }

    public function renewals(Request $request)
    {
        $days = (int) $request->get('days', 30);
        $days = max(1, min(90, $days));

        $hostingDue = HostingAccount::where('status', 'active')
            ->whereBetween('next_due_date', [now()->toDateString(), now()->addDays($days)->toDateString()])
            ->with(['user', 'hostingPackage'])->orderBy('next_due_date')->get();

        $domainsDue = Domain::where('status', 'active')
            ->whereBetween('expiry_date', [now()->toDateString(), now()->addDays($days)->toDateString()])
            ->with('user')->orderBy('expiry_date')->get();

        return view('admin.reports.renewals', compact('hostingDue', 'domainsDue', 'days'));
    }

    public function tax(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->toDateString());
        $to   = $request->get('to',   now()->toDateString());

        $total = Invoice::whereBetween('date_created', [$from, $to])->where('status', 'paid')->sum('tax');

        $byCountry = Invoice::whereBetween('date_created', [$from, $to])
            ->where('status', 'paid')
            ->join('users', 'invoices.user_id', '=', 'users.id')
            ->select('users.country', DB::raw('SUM(invoices.tax) as total'))
            ->groupBy('users.country')->orderByDesc('total')->get();

        return view('admin.reports.tax', compact('total', 'byCountry', 'from', 'to'));
    }

    public function export(Request $request)
    {
        // CSV export placeholder — returns JSON for now
        return response()->json(['message' => 'Export functionality coming soon.']);
    }
}
