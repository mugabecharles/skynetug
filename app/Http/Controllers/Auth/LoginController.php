<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = strtolower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)]),
            ]);
        }

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 900); // 15 minutes

            AuditLog::create([
                'user_id'       => null,
                'action_type'   => 'failed_login',
                'resource_type' => 'user',
                'resource_id'   => null,
                'description'   => "Failed login attempt for email: {$request->email}",
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->userAgent(),
            ]);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Your account has been disabled. Please contact support.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        AuditLog::create([
            'user_id'       => $user->id,
            'action_type'   => 'login',
            'resource_type' => 'user',
            'resource_id'   => $user->id,
            'description'   => "User logged in successfully.",
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->userAgent(),
        ]);

        $request->session()->regenerate();

        // If there's a specific intended URL (e.g. cart), go there regardless of role
        $intended = $request->session()->pull('url.intended');
        if ($intended) {
            return redirect($intended);
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard.index');
    }

    public function logout(Request $request)
    {
        AuditLog::create([
            'user_id'       => Auth::id(),
            'action_type'   => 'logout',
            'resource_type' => 'user',
            'resource_id'   => Auth::id(),
            'description'   => 'User logged out.',
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->userAgent(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
