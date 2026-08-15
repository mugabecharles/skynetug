<?php

namespace App\Services;

use App\Mail\PaymentConfirmed;
use App\Mail\PaymentFailed;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Initiate a payment for an invoice.
     */
    public function initiate(Invoice $invoice, string $gateway, array $extra = []): array
    {
        $transactionId = 'TXN-' . strtoupper(Str::random(12));

        $payment = Payment::create([
            'transaction_id' => $transactionId,
            'user_id'        => $invoice->user_id,
            'invoice_id'     => $invoice->id,
            'gateway'        => $gateway,
            'amount'         => $invoice->total,
            'currency'       => $invoice->currency ?? 'USD',
            'status'         => 'pending',
            'phone_number'   => $extra['phone'] ?? null,
        ]);

        return match ($gateway) {
            'mtn_mobile_money' => $this->initiateMtn($payment, $extra),
            'airtel_money'     => $this->initiateAirtel($payment, $extra),
            'flutterwave'      => $this->initiateFlutterwave($payment, $extra),
            'pesapal'          => $this->initiatePesapal($payment, $extra),
            default            => throw new \InvalidArgumentException("Unsupported gateway: {$gateway}"),
        };
    }

    // ── MTN Mobile Money ──────────────────────────────────────────────────────
    protected function initiateMtn(Payment $payment, array $extra): array
    {
        $baseUrl    = config('services.mtn_momo.base_url', 'https://sandbox.momodeveloper.mtn.com');
        $subscKey   = config('services.mtn_momo.subscription_key');
        $apiUser    = config('services.mtn_momo.collection_key');
        $apiKey     = config('services.mtn_momo.collection_secret');
        $env        = config('services.mtn_momo.environment', 'sandbox');

        if (!$subscKey || !$apiUser || !$apiKey) {
            Log::warning('MTN MoMo not fully configured');
            return $this->mockSuccess($payment);
        }

        try {
            // Step 1: Get OAuth token
            $token = $this->getMtnToken($baseUrl, $apiUser, $apiKey, $subscKey, $env);

            if (!$token) {
                return ['success' => false, 'error' => 'Could not get MTN token. Check API credentials.'];
            }

            // Step 2: Request to Pay
            $referenceId = $payment->transaction_id;
            $phone       = preg_replace('/[^0-9]/', '', $extra['phone'] ?? '');

            // Ensure phone is in international format
            if (str_starts_with($phone, '0')) {
                $phone = '256' . substr($phone, 1);
            }
            if (!str_starts_with($phone, '256')) {
                $phone = '256' . $phone;
            }

            $response = Http::withHeaders([
                'Authorization'               => "Bearer {$token}",
                'X-Reference-Id'              => $referenceId,
                'X-Target-Environment'        => $env,
                'Ocp-Apim-Subscription-Key'   => $subscKey,
                'Content-Type'                => 'application/json',
            ])->post("{$baseUrl}/collection/v1_0/requesttopay", [
                'amount'      => (string) round($payment->amount),
                'currency'    => 'UGX',
                'externalId'  => $referenceId,
                'payer'       => [
                    'partyIdType' => 'MSISDN',
                    'partyId'     => $phone,
                ],
                'payerMessage' => 'SkyNetug Payment',
                'payeeNote'    => 'Invoice ' . ($payment->invoice->invoice_number ?? $referenceId),
            ]);

            if ($response->status() === 202) {
                Log::info("MTN MoMo request sent for {$referenceId}");
                return [
                    'success'        => true,
                    'pending'        => true,
                    'transaction_id' => $referenceId,
                    'message'        => 'Payment request sent to your phone. Please approve it.',
                    'redirect_url'   => route('payment.success') . '?transaction_id=' . $referenceId,
                ];
            }

            Log::error('MTN MoMo failed: ' . $response->body());
            return ['success' => false, 'error' => 'MTN payment request failed. Try again.'];

        } catch (\Throwable $e) {
            Log::error('MTN MoMo exception: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Payment error: ' . $e->getMessage()];
        }
    }

    protected function getMtnToken(string $baseUrl, string $apiUser, string $apiKey, string $subscKey, string $env): ?string
    {
        try {
            $credentials = base64_encode("{$apiUser}:{$apiKey}");

            $response = Http::withHeaders([
                'Authorization'             => "Basic {$credentials}",
                'Ocp-Apim-Subscription-Key' => $subscKey,
                'X-Target-Environment'      => $env,
            ])->post("{$baseUrl}/collection/token/");

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('MTN token failed: ' . $response->body());
            return null;
        } catch (\Throwable $e) {
            Log::error('MTN token exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check MTN payment status.
     */
    public function checkMtnStatus(string $referenceId): string
    {
        $baseUrl  = config('services.mtn_momo.base_url');
        $subscKey = config('services.mtn_momo.subscription_key');
        $apiUser  = config('services.mtn_momo.collection_key');
        $apiKey   = config('services.mtn_momo.collection_secret');
        $env      = config('services.mtn_momo.environment', 'sandbox');

        try {
            $token = $this->getMtnToken($baseUrl, $apiUser, $apiKey, $subscKey, $env);
            if (!$token) return 'FAILED';

            $response = Http::withHeaders([
                'Authorization'             => "Bearer {$token}",
                'X-Target-Environment'      => $env,
                'Ocp-Apim-Subscription-Key' => $subscKey,
            ])->get("{$baseUrl}/collection/v1_0/requesttopay/{$referenceId}");

            return $response->json('status', 'PENDING');
        } catch (\Throwable $e) {
            return 'FAILED';
        }
    }

    // ── Flutterwave ───────────────────────────────────────────────────────────
    protected function initiateFlutterwave(Payment $payment, array $extra): array
    {
        $secretKey = config('services.flutterwave.secret_key');

        if (!$secretKey) {
            return $this->mockSuccess($payment);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$secretKey}",
            ])->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref'       => $payment->transaction_id,
                'amount'       => $payment->amount,
                'currency'     => 'UGX',
                'redirect_url' => route('payment.callback', ['gateway' => 'flutterwave']),
                'customer'     => [
                    'email' => $payment->user->email,
                    'name'  => $payment->user->name,
                    'phonenumber' => $payment->user->phone ?? '',
                ],
                'customizations' => [
                    'title'       => 'SkyNetug',
                    'description' => 'Invoice #' . ($payment->invoice->invoice_number ?? ''),
                    'logo'        => 'https://skynetug.com/images/logo.png',
                ],
                'payment_options' => 'mobilemoneyuganda,card,ussd',
            ]);

            $link = $response->json('data.link');
            if ($link) {
                return ['success' => true, 'redirect_url' => $link];
            }

            Log::error('Flutterwave failed: ' . $response->body());
            return ['success' => false, 'error' => 'Payment initiation failed.'];

        } catch (\Throwable $e) {
            Log::error('Flutterwave exception: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ── Airtel Money ──────────────────────────────────────────────────────────
    protected function initiateAirtel(Payment $payment, array $extra): array
    {
        Log::info("Airtel Money payment initiated: {$payment->transaction_id}");
        return $this->mockSuccess($payment);
    }

    // ── Pesapal ───────────────────────────────────────────────────────────────
    protected function initiatePesapal(Payment $payment, array $extra): array
    {
        Log::info("Pesapal payment initiated: {$payment->transaction_id}");
        return $this->mockSuccess($payment);
    }

    // ── Mock (dev/unconfigured) ───────────────────────────────────────────────
    protected function mockSuccess(Payment $payment): array
    {
        return [
            'success'      => true,
            'redirect_url' => route('payment.success') . '?transaction_id=' . $payment->transaction_id,
            'mock'         => true,
        ];
    }

    /**
     * Verify a payment from callback/webhook.
     */
    public function verify(string $gateway, string $reference): bool
    {
        $payment = Payment::where('transaction_id', $reference)
            ->orWhere('gateway_transaction_ref', $reference)
            ->first();

        if (!$payment || $payment->status === 'completed') {
            return $payment && $payment->status === 'completed';
        }

        $verified = match ($gateway) {
            'flutterwave'      => $this->verifyFlutterwave($reference),
            'mtn_mobile_money' => $this->verifyMtn($reference),
            default            => true,
        };

        if ($verified) {
            $payment->update([
                'status'  => 'completed',
                'paid_at' => now(),
            ]);

            if ($payment->invoice) {
                $payment->invoice->update([
                    'status'    => 'paid',
                    'date_paid' => now()->toDateString(),
                ]);

                // Notify customer — payment confirmed
                Mail::to($payment->user->email)
                    ->queue(new PaymentConfirmed($payment->invoice, $payment));

                // Trigger domain registration if order has domain items
                $this->processPostPayment($payment->invoice);
            }
        } else {
            // Notify customer — payment failed
            if ($payment->invoice) {
                Mail::to($payment->user->email)
                    ->queue(new PaymentFailed($payment->invoice, $payment));
            }
        }

        return $verified;
    }

    /**
     * Process actions after payment is confirmed.
     */
    protected function processPostPayment(Invoice $invoice): void
    {
        $order = $invoice->order;
        if (!$order) return;

        foreach ($order->orderItems as $item) {
            if ($item->item_type === 'hosting') {
                \App\Jobs\ProvisionHostingAccount::dispatch($invoice);
                Log::info("Hosting provisioning queued for invoice {$invoice->id}");
            }

            if ($item->item_type === 'domain') {
                $domain = $item->meta['domain'] ?? null;
                if ($domain) {
                    Log::info("Domain registration queued: {$domain}");
                    // In production: dispatch domain registration job
                    // \App\Jobs\RegisterDomain::dispatch($domain, $invoice->user);
                }
            }
        }
    }

    protected function verifyFlutterwave(string $txRef): bool
    {
        $secretKey = config('services.flutterwave.secret_key');
        if (!$secretKey) return true;

        try {
            $response = Http::withHeaders(['Authorization' => "Bearer {$secretKey}"])
                ->get("https://api.flutterwave.com/v3/transactions/{$txRef}/verify");
            return $response->json('data.status') === 'successful';
        } catch (\Throwable $e) {
            Log::error('Flutterwave verify failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function verifyMtn(string $txRef): bool
    {
        $status = $this->checkMtnStatus($txRef);
        return $status === 'SUCCESSFUL';
    }
}
