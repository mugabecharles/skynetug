<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\HostingPackage;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        $totals = $this->cart->totals();
        return view('cart.index', compact('totals'));
    }

    public function addHosting(Request $request, string $slug)
    {
        $package = HostingPackage::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $request->validate([
            'domain' => ['required', 'string', 'min:3', 'max:100'],
            'cycle'  => ['nullable', 'in:monthly,yearly,biennially'],
        ]);

        $this->cart->addHosting($package, $request->domain, $request->cycle ?? 'yearly');

        return redirect()->route('cart.index')
            ->with('success', $package->name . ' added to cart!');
    }

    public function addDomain(Request $request)
    {
        $request->validate([
            'domain' => ['required', 'string'],
            'price'  => ['required', 'numeric', 'min:0'],
        ]);

        $domain = strtolower(trim($request->domain));
        $tld    = '.' . implode('.', array_slice(explode('.', $domain), 1));
        $this->cart->addDomain($domain, $tld, $request->price);

        return redirect()->route('cart.index')
            ->with('success', $domain . ' added to cart!');
    }

    public function remove(Request $request)
    {
        $this->cart->remove($request->item_id);
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function applyCoupon(Request $request)
    {
        $result = $this->cart->applyCoupon($request->coupon_code ?? '');

        if ($result['success']) {
            return redirect()->route('cart.index')->with('success', $result['message']);
        }
        return redirect()->route('cart.index')->with('error', $result['message']);
    }

    public function removeCoupon()
    {
        $this->cart->removeCoupon();
        return redirect()->route('cart.index')->with('success', 'Coupon removed.');
    }

    public function checkout(Request $request)
    {
        $totals = $this->cart->totals();

        if (empty($totals['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        if (!auth()->check()) {
            Session::put('redirect_after_login', route('cart.checkout'));
            return redirect()->route('login')->with('info', 'Please log in to complete your order.');
        }

        return view('cart.checkout', compact('totals'));
    }

    public function placeOrder(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $totals = $this->cart->totals();

        if (empty($totals['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Build items array for OrderController
        $items = [];
        foreach ($totals['items'] as $item) {
            $items[] = [
                'type'          => $item['type'],
                'package_id'    => $item['package_id'] ?? null,
                'name'          => $item['name'],
                'billing_cycle' => $item['billing_cycle'],
                'price'         => $item['price'],
                'quantity'      => 1,
                'meta'          => ['domain' => $item['domain'] ?? null],
            ];
        }

        $request->merge([
            'items'       => $items,
            'coupon_code' => $totals['coupon']['code'] ?? null,
        ]);

        // Use OrderController checkout
        $orderController = app(\App\Http\Controllers\Order\OrderController::class);
        $response = $orderController->checkout($request);

        // Clear cart on success
        $this->cart->clear();

        return $response;
    }
}
