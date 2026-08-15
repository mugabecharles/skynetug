<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TldPricing;
use Illuminate\Http\Request;

class TldPricingController extends Controller
{
    public function index()
    {
        $tlds = TldPricing::orderBy('sort_order')->orderBy('tld')->get();
        return view('admin.tld-pricing.index', compact('tlds'));
    }

    public function create()
    {
        return view('admin.tld-pricing.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tld'            => ['required', 'string', 'max:20', 'unique:tld_pricing,tld'],
            'register_price' => ['required', 'numeric', 'min:0'],
            'renew_price'    => ['required', 'numeric', 'min:0'],
            'transfer_price' => ['nullable', 'numeric', 'min:0'],
            'is_active'      => ['nullable', 'boolean'],
            'is_popular'     => ['nullable', 'boolean'],
            'sort_order'     => ['nullable', 'integer'],
        ]);

        // Ensure TLD starts with dot
        if (!str_starts_with($data['tld'], '.')) {
            $data['tld'] = '.' . $data['tld'];
        }

        $data['is_active']      = $request->boolean('is_active', true);
        $data['is_popular']     = $request->boolean('is_popular', false);
        $data['transfer_price'] = $data['transfer_price'] ?? $data['register_price'];

        TldPricing::create($data);

        return redirect()->route('admin.tld-pricing.index')
            ->with('success', 'TLD "' . $data['tld'] . '" added successfully.');
    }

    public function edit(TldPricing $tldPricing)
    {
        return view('admin.tld-pricing.edit', compact('tldPricing'));
    }

    public function update(Request $request, TldPricing $tldPricing)
    {
        $data = $request->validate([
            'register_price' => ['required', 'numeric', 'min:0'],
            'renew_price'    => ['required', 'numeric', 'min:0'],
            'transfer_price' => ['nullable', 'numeric', 'min:0'],
            'is_active'      => ['nullable', 'boolean'],
            'is_popular'     => ['nullable', 'boolean'],
            'sort_order'     => ['nullable', 'integer'],
        ]);

        $data['is_active']      = $request->boolean('is_active');
        $data['is_popular']     = $request->boolean('is_popular');
        $data['transfer_price'] = $data['transfer_price'] ?? $data['register_price'];

        $tldPricing->update($data);

        return redirect()->route('admin.tld-pricing.index')
            ->with('success', '"' . $tldPricing->tld . '" updated successfully.');
    }

    public function destroy(TldPricing $tldPricing)
    {
        $tldPricing->delete();
        return redirect()->route('admin.tld-pricing.index')
            ->with('success', 'TLD deleted.');
    }

    // Bulk update all prices at once
    public function bulkUpdate(Request $request)
    {
        $rows = $request->validate([
            'tlds'                   => ['required', 'array'],
            'tlds.*.id'              => ['required', 'exists:tld_pricing,id'],
            'tlds.*.register_price'  => ['required', 'numeric', 'min:0'],
            'tlds.*.renew_price'     => ['required', 'numeric', 'min:0'],
            'tlds.*.is_active'       => ['nullable', 'boolean'],
        ]);

        foreach ($rows['tlds'] as $row) {
            TldPricing::where('id', $row['id'])->update([
                'register_price' => $row['register_price'],
                'renew_price'    => $row['renew_price'],
                'is_active'      => isset($row['is_active']) ? 1 : 0,
            ]);
        }

        return redirect()->route('admin.tld-pricing.index')
            ->with('success', 'All prices updated successfully.');
    }
}
