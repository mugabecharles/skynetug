<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliatePayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateManagementController extends Controller
{
    public function index()
    {
        $affiliates = Affiliate::with(['user', 'payouts'])->latest()->paginate(20);
        return view('admin.affiliates.index', compact('affiliates'));
    }

    public function approvePayout(int $id)
    {
        $payout = AffiliatePayout::with('affiliate')->findOrFail($id);

        $payout->update([
            'status'       => 'paid',
            'processed_by' => Auth::id(),
            'paid_at'      => now(),
        ]);

        // Deduct from affiliate balance
        $payout->affiliate->decrement('balance', $payout->amount);
        $payout->affiliate->increment('total_paid', $payout->amount);

        return back()->with('success', 'Payout approved and marked as paid.');
    }
}
