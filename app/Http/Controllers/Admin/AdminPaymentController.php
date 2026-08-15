<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'invoice'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('transaction_id', 'like', "%$s%")
                ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%$s%")));
        }
        if ($request->filled('gateway'))   { $query->where('gateway', $request->gateway); }
        if ($request->filled('status'))    { $query->where('status', $request->status); }
        if ($request->filled('date_from')) { $query->whereDate('created_at', '>=', $request->date_from); }

        $payments = $query->paginate(25)->withQueryString();

        $stats = [
            'total'   => Payment::where('status', 'completed')->sum('amount'),
            'month'   => Payment::where('status', 'completed')->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum('amount'),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed'  => Payment::where('status', 'failed')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }
}
