<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\User;
use App\Models\Affiliate;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'country'  => ['nullable', 'string', 'size:2'],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);

        $referralCode = Str::upper(Str::random(8));

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone'         => $request->phone,
            'country'       => $request->country ?? 'UG',
            'role'          => 'customer',
            'referral_code' => $referralCode,
        ]);

        // Handle affiliate referral
        if ($request->filled('ref')) {
            $referrer = User::where('referral_code', $request->ref)->first();
            if ($referrer && $referrer->affiliate) {
                \App\Models\AffiliateReferral::create([
                    'affiliate_id'     => $referrer->affiliate->id,
                    'referred_user_id' => $user->id,
                    'commission'       => 0, // Updated when they make a purchase
                    'status'           => 'pending',
                ]);
            }
        }

        event(new Registered($user));
        Auth::login($user);

        // Send welcome email (queued)
        Mail::to($user->email)->queue(new WelcomeEmail($user));

        // Respect any intended redirect (e.g. back to cart after adding an item as guest)
        return redirect()->intended(route('dashboard.index'));
    }
}
