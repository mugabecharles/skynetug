@extends('emails.layout')
@section('content')
<div class="alert-danger">
    🔴 Your hosting account has been suspended
</div>

<p class="greeting">Account Suspended</p>

<p class="message">
    Hi {{ $account->user->name }},<br>
    Your hosting account for <strong>{{ $account->domain }}</strong> has been suspended
    due to an overdue invoice. Your website is currently offline.
</p>

<div class="info-box">
    <table>
        <tr><td>Domain</td><td>{{ $account->domain }}</td></tr>
        <tr><td>Suspended on</td><td>{{ now()->format('d M Y, H:i') }}</td></tr>
        <tr><td>Reason</td><td>{{ $account->suspension_reason }}</td></tr>
    </table>
</div>

<div class="alert-warning">
    ℹ️ To reactivate your account, please pay your outstanding invoice.
    Your account will be unsuspended automatically within 5 minutes of payment.
    Account data is retained for 30 days before permanent deletion.
</div>

<p style="text-align:center; margin:28px 0 16px;">
    <a href="{{ route('dashboard.invoices.index') }}" class="btn" style="background:#EF4444;">
        Pay Outstanding Invoice →
    </a>
</p>

<p class="message" style="font-size:.88rem; color:#6B7280;">
    If you believe this suspension was made in error, please
    <a href="{{ route('dashboard.tickets.create') }}" style="color:#0066FF;">open a support ticket</a>
    immediately.
</p>
@endsection
