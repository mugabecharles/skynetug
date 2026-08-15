<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Domain;
use App\Models\HostingAccount;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Server;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_customers'   => User::where('role', 'customer')->count(),
            'total_orders'      => \App\Models\Order::count(),
            'monthly_revenue'   => Payment::where('status', 'completed')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
            'total_revenue'     => Payment::where('status', 'completed')->sum('amount'),
            'active_domains'    => Domain::where('status', 'active')->count(),
            'active_hosting'    => HostingAccount::where('status', 'active')->count(),
            'pending_tickets'   => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
            'unpaid_invoices'   => Invoice::whereIn('status', ['unpaid', 'overdue'])->count(),
        ];

        // Monthly revenue chart data (last 12 months)
        $chartData = Payment::where('status', 'completed')
            ->where('paid_at', '>=', now()->subMonths(12))
            ->selectRaw('YEAR(paid_at) as year, MONTH(paid_at) as month, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $servers = Server::where('is_active', true)->get();

        $recentActivity = AuditLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'servers', 'recentActivity'));
    }

    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);
        $actionTypes = AuditLog::distinct()->pluck('action_type')->sort()->values();

        return view('admin.audit-logs', compact('logs', 'actionTypes'));
    }
}
