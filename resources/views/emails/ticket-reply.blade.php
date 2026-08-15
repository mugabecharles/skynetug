@extends('emails.layout')
@section('content')
<p class="greeting">New Reply on Ticket #{{ $ticket->ticket_number }}</p>

<p class="message">
    Hi {{ $recipientName }},<br>
    @if($isStaffReply)
        Our support team has replied to your ticket <strong>{{ $ticket->subject }}</strong>.
    @else
        The customer has replied to ticket <strong>{{ $ticket->subject }}</strong>.
    @endif
</p>

<div class="info-box">
    <table>
        <tr><td>Ticket #</td><td>{{ $ticket->ticket_number }}</td></tr>
        <tr><td>Subject</td><td>{{ $ticket->subject }}</td></tr>
        <tr><td>Status</td><td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td></tr>
        <tr><td>Replied by</td><td>{{ $reply->user->name }}{{ $isStaffReply ? ' (Support Team)' : '' }}</td></tr>
        <tr><td>Reply time</td><td>{{ $reply->created_at->format('d M Y, H:i') }}</td></tr>
    </table>
</div>

<div class="info-box" style="margin-top:0;">
    <strong style="font-size:.85rem; color:#6B7280; display:block; margin-bottom:8px;">REPLY</strong>
    <p style="margin:0; color:#374151; font-size:.9rem; line-height:1.6;">{{ $reply->message }}</p>
</div>

<p style="text-align:center; margin:28px 0 16px;">
    @if($isStaffReply)
        <a href="{{ route('dashboard.tickets.show', $ticket->id) }}" class="btn">View &amp; Reply →</a>
    @else
        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn">View &amp; Reply →</a>
    @endif
</p>
@endsection
