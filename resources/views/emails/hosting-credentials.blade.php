@extends('emails.layout')
@section('content')
<div class="alert-success">
    🚀 Your hosting account is live!
</div>

<p class="greeting">Hosting Account Ready</p>

<p class="message">
    Hi {{ $account->user->name }},<br>
    Your hosting account for <strong>{{ $account->domain }}</strong> has been set up successfully
    and is ready to use. Your login credentials are below — keep them safe.
</p>

<div class="credential-box">
    <table>
        <tr><td>Domain</td><td>{{ $account->domain }}</td></tr>
        <tr><td>cPanel URL</td><td>{{ $account->cpanel_url ?? 'https://server1.skynetug.com:2083' }}</td></tr>
        <tr><td>Username</td><td>{{ $account->username }}</td></tr>
        <tr><td>Password</td><td>{{ $password }}</td></tr>
        <tr><td>Server</td><td>{{ $account->server?->hostname ?? 'server1.skynetug.com' }}</td></tr>
        <tr><td>Nameserver 1</td><td>{{ $account->server?->ns1 ?? 'ns1.skynetug.com' }}</td></tr>
        <tr><td>Nameserver 2</td><td>{{ $account->server?->ns2 ?? 'ns2.skynetug.com' }}</td></tr>
    </table>
</div>

<p style="text-align:center; margin:28px 0 16px;">
    <a href="{{ route('dashboard.hosting.show', $account->id) }}" class="btn">Go to My Hosting →</a>
</p>

<div class="alert-warning">
    🔒 For security, please change your cPanel password after your first login.
    Never share your credentials with anyone.
</div>

<p class="message" style="font-size:.88rem; color:#6B7280;">
    Need help setting up WordPress or your email? Visit our
    <a href="{{ route('kb.index') }}" style="color:#0066FF;">Knowledge Base</a>
    or open a support ticket and we'll guide you step by step.
</p>
@endsection
