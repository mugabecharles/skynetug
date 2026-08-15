<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('user')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('invoice_number', 'like', "%$s%")
                ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%$s%")));
        }
        if ($request->filled('status'))    { $query->where('status', $request->status); }
        if ($request->filled('date_from')) { $query->whereDate('date_created', '>=', $request->date_from); }
        if ($request->filled('date_to'))   { $query->whereDate('date_created', '<=', $request->date_to); }

        $invoices = $query->paginate(25)->withQueryString();
        return view('admin.invoices.index', compact('invoices'));
    }

    public function show(int $id)
    {
        $invoice = Invoice::with(['user', 'items', 'payments'])->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function refund(Request $request, int $id)
    {
        $invoice = Invoice::where('status', 'paid')->findOrFail($id);
        $payment = $invoice->payments()->where('status', 'completed')->latest()->first();

        if (!$payment) {
            return back()->with('error', 'No completed payment found for this invoice.');
        }

        Refund::create([
            'payment_id'   => $payment->id,
            'invoice_id'   => $invoice->id,
            'processed_by' => Auth::id(),
            'amount'       => $invoice->total,
            'reason'       => 'Admin-initiated refund',
            'status'       => 'completed',
            'processed_at' => now(),
        ]);

        $invoice->update(['status' => 'refunded']);
        $payment->update(['status' => 'refunded']);

        return back()->with('success', "Refund processed for invoice {$invoice->invoice_number}.");
    }
}
