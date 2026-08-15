<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceGenerated;
use App\Models\Coupon;
use App\Models\HostingPackage;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function hostingCheckout(string $slug)
    {
        $package = HostingPackage::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$package) {
            $package = HostingPackage::where('is_active', true)
                ->where('type', 'shared')
                ->orderBy('sort_order')
                ->first();
        }

        if (!$package) {
            return redirect()->route('hosting.shared')
                ->with('error', 'Hosting package not found. Please select a plan.');
        }

        return view('order.hosting-checkout', compact('package'));
    }

    public function domainCheckout(Request $request)
    {
        $domain = $request->get('domain');
        return view('order.domain-checkout', compact('domain'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items'                 => ['required', 'array', 'min:1'],
            'items.*.type'          => ['required', 'in:hosting,domain,ssl,email,backup,design'],
            'items.*.package_id'    => ['nullable', 'exists:hosting_packages,id'],
            'items.*.name'          => ['required', 'string'],
            'items.*.billing_cycle' => ['required', 'in:monthly,yearly,biennially'],
            'items.*.price'         => ['nullable', 'numeric', 'min:0'],
            'items.*.quantity'      => ['nullable', 'integer', 'min:1'],
            'items.*.meta.domain'   => ['nullable', 'string'],
            'coupon_code'           => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($request) {
            $subtotal = 0;
            $items    = [];

            foreach ($request->items as $item) {
                if ($item['package_id'] ?? null) {
                    $package = HostingPackage::findOrFail($item['package_id']);
                    $price   = match ($item['billing_cycle']) {
                        'monthly'    => $package->price_monthly,
                        'biennially' => $package->price_biennially,
                        default      => $package->price_yearly,
                    };
                } else {
                    $price = (float) ($item['price'] ?? 0);
                }

                $subtotal += $price * ($item['quantity'] ?? 1);
                $items[]   = array_merge($item, ['unit_price' => $price]);
            }

            // Apply coupon
            $discount = 0;
            $coupon   = null;
            $couponId = null;

            if ($request->filled('coupon_code')) {
                $coupon = Coupon::where('code', $request->coupon_code)->first();
                if ($coupon && $coupon->isValid()) {
                    $discount = $coupon->type === 'percentage'
                        ? ($subtotal * $coupon->value / 100)
                        : min($coupon->value, $subtotal);
                    $couponId = $coupon->id;
                }
            }

            $tax   = 0;
            $total = $subtotal - $discount + $tax;

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id'      => Auth::id(),
                'status'       => 'pending',
                'subtotal'     => $subtotal,
                'tax'          => $tax,
                'discount'     => $discount,
                'total'        => $total,
                'currency'     => 'USD',
                'coupon_id'    => $couponId,
            ]);

            foreach ($items as $item) {
                $lineTotal = $item['unit_price'] * ($item['quantity'] ?? 1);
                $meta      = $item['meta'] ?? [];
                if (!is_array($meta)) {
                    $meta = [];
                }

                OrderItem::create([
                    'order_id'           => $order->id,
                    'hosting_package_id' => $item['package_id'] ?? null,
                    'item_type'          => $item['type'],
                    'item_name'          => $item['name'],
                    'billing_cycle'      => $item['billing_cycle'],
                    'unit_price'         => $item['unit_price'],
                    'quantity'           => $item['quantity'] ?? 1,
                    'total'              => $lineTotal,
                    'service_start'      => now()->toDateString(),
                    'service_end'        => $this->calcEndDate($item['billing_cycle']),
                    'meta'               => $meta ?: null,
                ]);
            }

            // Generate invoice
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'user_id'        => Auth::id(),
                'order_id'       => $order->id,
                'status'         => 'unpaid',
                'type'           => 'new_order',
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'total'          => $total,
                'currency'       => 'USD',
                'date_created'   => now()->toDateString(),
                'date_due'       => now()->addDays(7)->toDateString(),
            ]);

            foreach ($items as $item) {
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'description' => $item['name'] . ' (' . ucfirst($item['billing_cycle']) . ')',
                    'amount'      => $item['unit_price'],
                    'quantity'    => $item['quantity'] ?? 1,
                ]);
            }

            if ($coupon) {
                $coupon->increment('usage_count');
            }

            // Clear the session cart
            Session::forget('cart');
            Session::forget('coupon');

            // Send invoice notification (queued)
            Mail::to(Auth::user()->email)->queue(new InvoiceGenerated($invoice));

            return redirect()->route('order.confirm', $order->id);
        });
    }

    public function confirm(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderItems', 'invoices']);
        $invoice = $order->invoices->first();

        return view('order.confirm', compact('order', 'invoice'));
    }

    protected function calcEndDate(string $cycle): string
    {
        return match ($cycle) {
            'monthly'    => now()->addMonth()->toDateString(),
            'biennially' => now()->addYears(2)->toDateString(),
            default      => now()->addYear()->toDateString(),
        };
    }
}
