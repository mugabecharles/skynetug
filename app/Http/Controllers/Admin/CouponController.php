<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'        => ['required', 'string', 'max:50', 'unique:coupons'],
            'name'        => ['required', 'string', 'max:100'],
            'type'        => ['required', 'in:fixed,percentage'],
            'value'       => ['required', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
            'starts_at'   => ['nullable', 'date'],
            'expires_at'  => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);
        $data['is_active']   = true;
        $data['usage_limit'] = $data['usage_limit'] ?? 0;

        Coupon::create($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'type'        => ['required', 'in:fixed,percentage'],
            'value'       => ['required', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
            'starts_at'   => ['nullable', 'date'],
            'expires_at'  => ['nullable', 'date'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $coupon->update($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted.');
    }
}
