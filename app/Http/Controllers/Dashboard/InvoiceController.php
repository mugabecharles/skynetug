<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoicePdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct(protected InvoicePdfService $pdfService) {}

    public function index(Request $request)
    {
        $query = Auth::user()->invoices()->with('order')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(15);

        return view('dashboard.invoices.index', compact('invoices'));
    }

    public function show(int $id)
    {
        $invoice = Auth::user()->invoices()
            ->with(['items', 'payments', 'order'])
            ->findOrFail($id);

        return view('dashboard.invoices.show', compact('invoice'));
    }

    public function downloadPdf(int $id)
    {
        $invoice = Auth::user()->invoices()
            ->with(['items', 'payments'])
            ->findOrFail($id);

        return $this->pdfService->download($invoice);
    }

    public function pay(Request $request, int $id)
    {
        $invoice = Auth::user()->invoices()
            ->whereIn('status', ['unpaid', 'overdue'])
            ->findOrFail($id);

        $request->validate([
            'gateway' => ['required', 'in:mtn_mobile_money,airtel_money,flutterwave,pesapal'],
        ]);

        return redirect()->route('payment.initiate', [
            'invoice_id' => $invoice->id,
            'gateway'    => $request->gateway,
        ]);
    }
}
