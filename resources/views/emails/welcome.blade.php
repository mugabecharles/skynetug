@extends('emails.layout')
@section('content')
<p class="greeting">Welcome to SkyNetug, {{ $user->name }}! 🎉</p>

<p class="message">
    Your account has been created successfully. You can now purchase hosting plans,
    register domain names, and manage all your services from your dashboard.
</p>

<div class="info-box">
    <table>
        <tr><td>Email</td><td>{{ $user->email }}</td></tr>
        <tr><td>Account created</td><td>{{ now()->format('d M Y, H:i') }}</td></tr>
        <tr><td>Referral code</td><td>{{ $user->referral_code }}</td></tr>
    </table>
</div>

<p style="text-align:center; margin:28px 0 16px;">
    <a href="{{ route('dashboard.index') }}" class="btn">Go to My Dashboard →</a>
</p>

<p class="message" style="font-size:.88rem; color:#6B7280;">
    Share your referral code <strong>{{ $user->referral_code }}</strong> and earn commission
    whenever someone signs up and makes a purchase.
</p>
@endsection
