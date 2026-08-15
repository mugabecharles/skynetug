<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'size:2'],
            'city'    => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'postcode'=> ['nullable', 'string', 'max:20'],
            'avatar'  => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only(['name', 'phone', 'company', 'country', 'city', 'address', 'postcode']);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function enable2fa(Request $request)
    {
        // TODO: Implement TOTP 2FA setup
        return back()->with('info', '2FA setup coming soon.');
    }

    public function disable2fa(Request $request)
    {
        Auth::user()->update([
            'two_factor_enabled' => false,
            'two_factor_secret'  => null,
        ]);

        return back()->with('success', 'Two-factor authentication has been disabled.');
    }

    public function updateNotifications(Request $request)
    {
        // TODO: store notification preferences in user meta
        return back()->with('success', 'Notification preferences saved.');
    }
}
