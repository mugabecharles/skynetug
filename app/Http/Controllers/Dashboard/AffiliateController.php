<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliatePayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $affiliate = $user->affiliate;

        if ($affiliate) {
            $affiliate->load(['referrals.referredUser', 'payouts']);
        }

        return view('dashboard.affiliate.index', compact('user', 'affiliate'));
    }

    public function enroll()
    {
        $user = Auth::user();

        if ($user->affiliate) {
            return back()->with('info', 'You are already enrolled in the affiliate program.');
        }

        Affiliate::create([
            'user_id'          => $user->id,
            'referral_code'    => strtoupper(Str::random(10)),
            'commission_rate'  => 10.00,
            'status'           => 'active',
        ]);

        return back()->with('success', 'You have successfully enrolled in the affiliate program!');
    }

    public function requestPayout(Request $request)
    {
        $affiliate = Auth::user()->affiliate;

        if (!$affiliate) {
            return back()->with('error', 'You are not enrolled in the affiliate program.');
        }

        $minPayout = config('affiliate.min_payout', 50000);

        if ($affiliate->balance < $minPayout) {
            return back()->with('error', "Minimum payout amount is $ " . number_format($minPayout) . ". Your balance is $ " . number_format($affiliate->balance));
        }

        AffiliatePayout::create([
            'affiliate_id'   => $affiliate->id,
            'amount'         => $affiliate->balance,
            'status'         => 'pending',
            'payment_method' => $request->payment_method ?? 'mobile_money',
        ]);

        return back()->with('success', 'Payout request submitted. It will be reviewed within 3 business days.');
    }
}
