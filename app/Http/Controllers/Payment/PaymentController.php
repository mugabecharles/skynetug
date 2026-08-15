<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    /**
     * Initiate a payment.
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'invoice_id' => ['required', 'exists:invoices,id'],
            'gateway'    => ['required', 'in:mtn_mobile_money,airtel_money,flutterwave,pesapal'],
            'phone'      => ['nullable', 'string', 'max:20'],
        ]);

        $invoice = Invoice::where('user_id', Auth::id())
            ->whereIn('status', ['unpaid', 'overdue'])
            ->findOrFail($request->invoice_id);

        $result = $this->paymentService->initiate($invoice, $request->gateway, [
            'phone' => $request->phone,
        ]);

        if (!$result['success']) {
            return back()->with('error', $result['error'] ?? 'Payment initiation failed. Please try again.');
        }

        // MTN pending — show status polling page
        if (isset($result['pending']) && $result['pending']) {
            return redirect()->route('payment.pending', [
                'transaction_id' => $result['transaction_id'],
                'invoice_id'     => $invoice->id,
            ])->with('info', $result['message'] ?? 'Payment request sent to your phone. Please approve it.');
        }

        if (isset($result['redirect_url'])) {
            return redirect($result['redirect_url']);
        }

        return redirect()->route('payment.success')
            ->with('success', 'Payment completed!')
            ->with('transaction_id', $result['transaction_id'] ?? null);
    }

    /**
     * Show pending payment status page (for MTN MoMo polling).
     */
    public function pending(Request $request)
    {
        $payment = null;
        if ($request->filled('transaction_id')) {
            $payment = Payment::where('transaction_id', $request->transaction_id)
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('payment.pending', compact('payment'));
    }

    /**
     * AJAX endpoint to check MTN payment status.
     */
    public function checkStatus(Request $request)
    {
        $transactionId = $request->get('transaction_id');
        $payment = Payment::where('transaction_id', $transactionId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$payment) {
            return response()->json(['status' => 'NOT_FOUND']);
        }

        if ($payment->status === 'completed') {
            return response()->json(['status' => 'SUCCESSFUL', 'redirect' => route('payment.success')]);
        }

        // Check with MTN API
        if ($payment->gateway === 'mtn_mobile_money') {
            $status = $this->paymentService->checkMtnStatus($transactionId);

            if ($status === 'SUCCESSFUL') {
                $this->paymentService->verify('mtn_mobile_money', $transactionId);
                return response()->json(['status' => 'SUCCESSFUL', 'redirect' => route('payment.success')]);
            }

            if ($status === 'FAILED') {
                $payment->update(['status' => 'failed']);
                return response()->json(['status' => 'FAILED', 'redirect' => route('payment.failed')]);
            }
        }

        return response()->json(['status' => 'PENDING']);
    }

    /**
     * Handle payment gateway callback.
     */
    public function callback(Request $request, string $gateway)
    {
        $reference = $request->get('transaction_id')
            ?? $request->get('tx_ref')
            ?? $request->get('reference');

        if (!$reference) {
            return redirect()->route('payment.failed')->with('error', 'Invalid payment callback.');
        }

        $verified = $this->paymentService->verify($gateway, $reference);

        if ($verified) {
            return redirect()->route('payment.success')
                ->with('success', 'Payment completed successfully!');
        }

        return redirect()->route('payment.failed')
            ->with('error', 'Payment verification failed. Please contact support.');
    }

    /**
     * Handle webhook from payment gateways.
     */
    public function webhook(Request $request, string $gateway)
    {
        Log::info("Webhook received from {$gateway}", $request->all());

        if ($gateway === 'flutterwave') {
            $hash = $request->header('verif-hash');
            if ($hash !== config('services.flutterwave.secret_hash')) {
                return response()->json(['status' => 'error'], 401);
            }
        }

        $reference = match ($gateway) {
            'flutterwave' => $request->json('data.tx_ref'),
            'mtn_mobile_money' => $request->get('externalId'),
            default       => $request->get('reference'),
        };

        if ($reference) {
            $this->paymentService->verify($gateway, $reference);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Payment success page.
     */
    public function success(Request $request)
    {
        $payment = null;
        if ($request->filled('transaction_id')) {
            $payment = Payment::where('transaction_id', $request->transaction_id)
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('payment.success', compact('payment'));
    }

    /**
     * Payment failed page.
     */
    public function failed(Request $request)
    {
        return view('payment.failed');
    }
}
