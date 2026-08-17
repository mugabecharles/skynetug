@extends('emails.layout')
@section('content')
<div class="alert-success">
    🔄 Domain transfer initiated!
</div>

<p class="greeting">Domain Transfer in Progress</p>

<p class="message">
    Hi {{ $domain->user->name }},<br>
    Your domain transfer request for <strong>{{ $domain->domain_name }}</strong> has been submitted
    successfully. The transfer is now pending approval from your previous registrar.
</p>

<div class="info-box">
    <table>
        <tr><td>Domain name</td><td style="font-weight:700;">{{ $domain->domain_name }}</td></tr>
        <tr><td>Transfer status</td><td style="color:#d97706;font-weight:700;">Pending</td></tr>
        <tr><td>Initiated on</td><td>{{ now()->format('d M Y, H:i') }}</td></tr>
        <tr><td>Expected completion</td><td>5 – 7 business days</td></tr>
        <tr><td>New nameservers</td><td>{{ $domain->nameserver_1 }}<br>{{ $domain->nameserver_2 }}</td></tr>
    </table>
</div>

<div class="alert-warning">
    ⚠️ <strong>Action may be required:</strong> Your previous registrar may send you a
    confirmation email asking you to approve the transfer. Please check your inbox and
    approve it promptly to avoid delays.
</div>

<p class="message">
    Your website will remain online throughout the transfer process. Once the transfer
    completes, your domain will appear as <strong>Active</strong> in your dashboard.
</p>

<p style="text-align:center; margin:28px 0 16px;">
    <a href="{{ route('dashboard.domains.index') }}" class="btn">View My Domains →</a>
</p>

<p class="message" style="font-size:.88rem; color:#6B7280;">
    If you have any questions or need help with the transfer, please
    <a href="{{ route('dashboard.tickets.create') }}" style="color:#0066FF;">open a support ticket</a>
    and our team will assist you.
</p>
@endsection
