@extends('emails.layout')
@section('content')
@php $urgent = $daysLeft <= 7; @endphp

<div class="{{ $urgent ? 'alert-danger' : 'alert-warning' }}">
    {{ $urgent ? '🚨' : '⚠️' }}
    Your domain expires in <strong>{{ $daysLeft }} {{ $daysLeft === 1 ? 'day' : 'days' }}</strong>!
</div>

<p class="greeting">Domain Expiry Reminder</p>

<p class="message">
    Hi {{ $domain->user->name }},<br>
    This is a reminder that your domain <strong>{{ $domain->domain_name }}</strong>
    is expiring on <strong>{{ $domain->expiry_date->format('d M Y') }}</strong>.
    That is in <strong>{{ $daysLeft }} {{ $daysLeft === 1 ? 'day' : 'days' }}</strong>.
</p>

<div class="info-box">
    <table>
        <tr><td>Domain name</td><td style="font-weight:700;">{{ $domain->domain_name }}</td></tr>
        <tr><td>Expiry date</td><td style="color:{{ $urgent ? '#dc2626' : '#d97706' }}; font-weight:700;">
            {{ $domain->expiry_date->format('d M Y') }}
        </td></tr>
        <tr><td>Renewal price</td><td>UGX {{ number_format($domain->renewal_price) }}/year</td></tr>
    </table>
</div>

@if($urgent)
<div class="alert-danger">
    ⚠️ If your domain expires, it will become available for anyone to register.
    You may lose your domain name permanently. Renew now to avoid this.
</div>
@endif

<p style="text-align:center; margin:28px 0 16px;">
    <a href="{{ route('dashboard.domains.show', $domain->id) }}" class="btn"
       style="background:{{ $urgent ? '#EF4444' : '#0066FF' }}">
        Renew {{ $domain->domain_name }} Now →
    </a>
</p>
@endsection
