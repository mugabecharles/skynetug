<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\HostingPackage;
use App\Services\DomainRegistrarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // ── Review page (Step 3) ─────────────────────────────────────
    public function review()
    {
        $cart  = Session::get('cart', []);
        $total = $this->calculateTotal($cart);
        return view('order.review', compact('cart', 'total'));
    }

    // ── View Cart ────────────────────────────────────────────────
    public function index()
    {
        $cart  = Session::get('cart', []);
        $total = $this->calculateTotal($cart);
        return view('order.cart', compact('cart', 'total'));
    }

    // ── Public "Add to Cart" — works for guests too ───────────────
    // Stores the item in session and goes straight to cart.
    // Login is only required at checkout, not at add-to-cart.
    public function addPublic(Request $request)
    {
        $request->validate([
            'type'          => ['required', 'in:hosting,domain,ssl,email'],
            'package_id'    => ['nullable', 'exists:hosting_packages,id'],
            'billing_cycle' => ['required', 'in:monthly,yearly,biennially'],
            'domain'        => ['nullable', 'string', 'max:100'],
            'price'         => ['nullable', 'numeric', 'min:0'],
            'name'          => ['required', 'string'],
        ]);

        // For hosting items without a domain name, show a quick domain-entry step
        if ($request->type === 'hosting' && empty($request->domain)) {
            Session::put('pending_cart_item', $request->only([
                'type', 'package_id', 'billing_cycle', 'price', 'name',
            ]));
            return redirect()->route('cart.domain.prompt');
        }

        $item = $this->buildItem($request);
        $cart = Session::get('cart', []);
        $cart[$item['key']] = $item;
        Session::put('cart', $cart);
        Session::forget('pending_cart_item');

        if ($request->ajax()) {
            return response()->json([
                'success'  => true,
                'count'    => count($cart),
                'total'    => $this->calculateTotal($cart)['total'],
                'redirect' => route('cart.index'),
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', '"' . $item['name'] . '" added to your cart!');
    }

    // ── Attach / update domain on an existing cart item ──────────
    public function attachDomain(Request $request, string $key)
    {
        $request->validate(['domain' => ['required', 'string', 'min:3', 'max:100']]);

        $cart = Session::get('cart', []);
        if (isset($cart[$key])) {
            $cart[$key]['meta']['domain'] = strtolower(trim($request->domain));
            Session::put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Domain attached successfully.');
    }

    // ── Domain prompt for hosting items added without a domain ────
    public function domainPrompt()
    {
        $pending = Session::get('pending_cart_item');
        if (empty($pending)) {
            return redirect()->route('cart.index');
        }
        return view('order.domain-prompt', compact('pending'));
    }

    public function domainPromptSubmit(Request $request)
    {
        $request->validate([
            'domain' => ['required', 'string', 'min:3', 'max:100'],
        ]);

        $pending = Session::get('pending_cart_item');
        if (empty($pending)) {
            return redirect()->route('cart.index');
        }

        // Merge domain into pending and build item
        $merged = new Request(array_merge($pending, ['domain' => strtolower(trim($request->domain))]));
        $item   = $this->buildItem($merged);

        $cart = Session::get('cart', []);
        $cart[$item['key']] = $item;
        Session::put('cart', $cart);
        Session::forget('pending_cart_item');

        return redirect()->route('cart.review')
            ->with('success', '"' . $item['name'] . '" added to your cart!');
    }

    // ── Add Item to Cart (authenticated) ────────────────────────
    public function add(Request $request)
    {
        $request->validate([
            'type'          => ['required', 'in:hosting,domain,ssl,email'],
            'package_id'    => ['nullable', 'exists:hosting_packages,id'],
            'billing_cycle' => ['required', 'in:monthly,yearly,biennially'],
            'domain'        => ['nullable', 'string', 'max:100'],
            'price'         => ['nullable', 'numeric', 'min:0'],
            'name'          => ['required', 'string'],
        ]);

        $item = $this->buildItem($request);
        $cart = Session::get('cart', []);
        $cart[$item['key']] = $item;
        Session::put('cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'count'   => count($cart),
                'total'   => $this->calculateTotal($cart)['total'],
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', '"' . $item['name'] . '" added to your cart!');
    }

    // ── Remove Item ──────────────────────────────────────────────
    public function remove(string $key)
    {
        $cart = Session::get('cart', []);
        unset($cart[$key]);

        if (empty($cart)) {
            // Cart is now empty — clear everything cleanly
            Session::forget('cart');
            Session::forget('coupon');
        } else {
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    // ── Update Billing Cycle ─────────────────────────────────────
    public function update(Request $request, string $key)
    {
        $cart = Session::get('cart', []);
        if (!isset($cart[$key])) {
            return back();
        }

        $item  = $cart[$key];
        $cycle = $request->billing_cycle ?? 'yearly';

        if ($item['package_id']) {
            $pkg   = HostingPackage::findOrFail($item['package_id']);
            $price = match ($cycle) {
                'monthly'    => (float) $pkg->price_monthly,
                'biennially' => (float) $pkg->price_biennially,
                default      => (float) $pkg->price_yearly,
            };
            $item['billing_cycle'] = $cycle;
            $item['price']         = $price;
            $newKey                = 'pkg_' . $pkg->id . '_' . $cycle;

            unset($cart[$key]);
            $cart[$newKey] = array_merge($item, ['key' => $newKey]);
        } else {
            $item['billing_cycle'] = $cycle;
            $cart[$key]            = $item;
        }

        Session::put('cart', $cart);
        return back()->with('success', 'Billing cycle updated.');
    }

    // ── Clear Cart ───────────────────────────────────────────────
    public function clear()
    {
        Session::forget('cart');
        Session::forget('coupon');
        Session::forget('pending_cart_item');
        return redirect()->route('cart.index');
    }

    // ── Apply Coupon ─────────────────────────────────────────────
    public function applyCoupon(Request $request)
    {
        $code   = strtoupper(trim($request->coupon_code ?? ''));
        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();

        if (!$coupon || !$coupon->isValid()) {
            return back()->with('error', 'Invalid or expired coupon code.');
        }

        Session::put('coupon', [
            'code'  => $coupon->code,
            'id'    => $coupon->id,
            'type'  => $coupon->type,
            'value' => (float) $coupon->value,
        ]);

        $label = $coupon->type === 'percentage'
            ? $coupon->value . '% off'
            : '$ ' . number_format($coupon->value) . ' off';

        return back()->with('success', 'Coupon "' . $code . '" applied — ' . $label . '!');
    }

    // ── Remove Coupon ────────────────────────────────────────────
    public function removeCoupon()
    {
        Session::forget('coupon');
        return back()->with('success', 'Coupon removed.');
    }

    // ── Calculate Totals ─────────────────────────────────────────
    public function calculateTotal(array $cart): array
    {
        $subtotal = array_sum(array_column($cart, 'price'));
        $coupon   = Session::get('coupon');
        $discount = 0;

        if ($coupon) {
            $discount = $coupon['type'] === 'percentage'
                ? round($subtotal * $coupon['value'] / 100, 2)
                : min((float) $coupon['value'], $subtotal);
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax'      => 0,
            'total'    => max(0, $subtotal - $discount),
            'coupon'   => $coupon,
            'count'    => count($cart),
        ];
    }

    // ── Build Item Array ──────────────────────────────────────────
    protected function buildItem(Request $request): array
    {
        if ($request->package_id) {
            $pkg   = HostingPackage::findOrFail($request->package_id);
            $cycle = $request->billing_cycle ?? 'yearly';
            $price = match ($cycle) {
                'monthly'    => (float) $pkg->price_monthly,
                'biennially' => (float) $pkg->price_biennially,
                default      => (float) $pkg->price_yearly,
            };
            return [
                'key'           => 'pkg_' . $pkg->id . '_' . $cycle,
                'type'          => 'hosting',
                'package_id'    => $pkg->id,
                'name'          => $pkg->name . ' Hosting',
                'billing_cycle' => $cycle,
                'price'         => $price,
                'quantity'      => 1,
                'meta'          => ['domain' => $request->domain ?? null],
            ];
        }

        // Domain or manual item
        $price = (float) ($request->price ?? 0);

        // If price is missing or zero for a domain item, look it up from TLD pricing
        if ($price <= 0 && $request->type === 'domain' && !empty($request->domain)) {
            $registrar  = app(DomainRegistrarService::class);
            $tldPricing = $registrar->getTldPricing();

            // Extract TLD from the domain name (e.g. "example.co.ug" → ".co.ug")
            // Sort TLDs by length descending so ".co.ug" matches before ".ug"
            $domainName = strtolower(trim($request->domain));
            $tlds = array_keys($tldPricing);
            usort($tlds, fn($a, $b) => strlen($b) - strlen($a));
            foreach ($tlds as $tld) {
                if (str_ends_with($domainName, $tld)) {
                    $price = (float) $tldPricing[$tld]['register'];
                    break;
                }
            }
        }

        $safeName = preg_replace('/[^a-z0-9]/', '', strtolower($request->name ?? 'item'));
        $key      = $request->type . '_' . $safeName . '_' . substr(md5($request->name . $price), 0, 8);

        return [
            'key'           => $key,
            'type'          => $request->type,
            'package_id'    => null,
            'name'          => $request->name,
            'billing_cycle' => $request->billing_cycle ?? 'yearly',
            'price'         => $price,
            'quantity'      => 1,
            'meta'          => ['domain' => $request->domain ?? $request->name ?? null],
        ];
    }
}
