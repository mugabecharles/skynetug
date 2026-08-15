<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\HostingPackage;
use Illuminate\Support\Facades\Session;

class CartService
{
    const SESSION_KEY = 'skynetug_cart';

    public function get(): array
    {
        return Session::get(self::SESSION_KEY, ['items' => [], 'coupon' => null]);
    }

    public function addHosting(HostingPackage $package, string $domain, string $cycle = 'yearly'): void
    {
        $cart = $this->get();

        // Remove existing hosting item
        $cart['items'] = array_filter($cart['items'], fn($i) => $i['type'] !== 'hosting');

        $price = match ($cycle) {
            'monthly'    => $package->price_monthly,
            'biennially' => $package->price_biennially,
            default      => $package->price_yearly,
        };

        $cart['items'][] = [
            'id'           => 'hosting-' . $package->id,
            'type'         => 'hosting',
            'package_id'   => $package->id,
            'name'         => $package->name,
            'domain'       => $domain,
            'billing_cycle'=> $cycle,
            'price'        => $price,
            'quantity'     => 1,
        ];

        Session::put(self::SESSION_KEY, $cart);
    }

    public function addDomain(string $domain, string $tld, float $price): void
    {
        $cart = $this->get();

        // Remove existing domain with same name
        $cart['items'] = array_filter($cart['items'], fn($i) => !($i['type'] === 'domain' && $i['domain'] === $domain));

        $cart['items'][] = [
            'id'           => 'domain-' . md5($domain),
            'type'         => 'domain',
            'name'         => 'Domain: ' . $domain,
            'domain'       => $domain,
            'tld'          => $tld,
            'billing_cycle'=> 'yearly',
            'price'        => $price,
            'quantity'     => 1,
        ];

        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(string $itemId): void
    {
        $cart = $this->get();
        $cart['items'] = array_values(array_filter($cart['items'], fn($i) => $i['id'] !== $itemId));
        Session::put(self::SESSION_KEY, $cart);
    }

    public function applyCoupon(string $code): array
    {
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (!$coupon || !$coupon->isValid()) {
            return ['success' => false, 'message' => 'Invalid or expired coupon code.'];
        }

        $cart = $this->get();
        $cart['coupon'] = [
            'code'    => $coupon->code,
            'type'    => $coupon->type,
            'value'   => $coupon->value,
            'id'      => $coupon->id,
        ];
        Session::put(self::SESSION_KEY, $cart);

        return ['success' => true, 'message' => 'Coupon applied! ' . ($coupon->type === 'percentage' ? $coupon->value . '% off' : '$' . $coupon->value . ' off')];
    }

    public function removeCoupon(): void
    {
        $cart = $this->get();
        $cart['coupon'] = null;
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function totals(): array
    {
        $cart     = $this->get();
        $items    = array_values($cart['items']);
        $subtotal = array_sum(array_column($items, 'price'));
        $discount = 0;

        if ($cart['coupon']) {
            $discount = $cart['coupon']['type'] === 'percentage'
                ? round($subtotal * $cart['coupon']['value'] / 100, 2)
                : min($cart['coupon']['value'], $subtotal);
        }

        return [
            'items'    => $items,
            'coupon'   => $cart['coupon'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total'    => max(0, $subtotal - $discount),
            'count'    => count($items),
        ];
    }
}
