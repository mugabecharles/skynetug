@extends('emails.layout')
@section('content')
<div class="alert-success">
    🌐 Your domain is registered!
</div>

<p class="greeting">Domain Registration Confirmed</p>

<p class="message">
    Hi {{ $domain->user->name }},<br>
    Your domain <strong>{{ $domain->domain_name }}</strong> has been registered successfully
    and is active. DNS propagation can take up to 24 hours globally.
</p>

<div class="info-box">
    <table>
        <tr><td>Domain name</td><td style="font-weight:700; font-size:1rem;">{{ $domain->domain_name }}</td></tr>
        <tr><td>Registered</td><td>{{ $domain->registration_date?->format('d M Y') ?? now()->format('d M Y') }}</td></tr>
        <tr><td>Expires</td><td>{{ $domain->expiry_date?->format('d M Y') ?? now()->addYear()->format('d M Y') }}</td></tr>
        <tr><td>Nameserver 1</td><td>{{ $domain->nameserver_1 ?? 'ns1.skynetug.com' }}</td></tr>
        <tr><td>Nameserver 2</td><td>{{ $domain->nameserver_2 ?? 'ns2.skynetug.com' }}</td></tr>
        <tr><td>Domain lock</td><td>{{ $domain->is_locked ? 'Enabled (recommended)' : 'Disabled' }}</td></tr>
    </table>
</div>

<p style="text-align:center; margin:28px 0 16px;">
    <a href="{{ route('dashboard.domains.show', $domain->id) }}" class="btn">Manage Domain →</a>
</p>

<p class="message" style="font-size:.88rem; color:#6B7280;">
    You can manage your DNS records, update nameservers, enable WHOIS privacy,
    and set auto-renewal from your domain dashboard.
</p>
@endsection
