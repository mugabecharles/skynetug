@extends('emails.layout')
@section('content')
<p class="greeting">New Support Ticket Opened</p>

<p class="message">
    A new support ticket has been submitted and requires attention.
</p>

<div class="info-box">
    <table>
        <tr><td>Ticket #</td><td style="font-weight:700;">{{ $ticket->ticket_number }}</td></tr>
        <tr><td>Customer</td><td>{{ $ticket->user->name }} ({{ $ticket->user->email }})</td></tr>
        <tr><td>Subject</td><td>{{ $ticket->subject }}</td></tr>
        <tr><td>Category</td><td>{{ ucfirst($ticket->category) }}</td></tr>
        <tr><td>Priority</td><td style="font-weight:700; color:{{ $ticket->priority === 'urgent' ? '#dc2626' : ($ticket->priority === 'high' ? '#d97706' : '#374151') }};">
            {{ ucfirst($ticket->priority) }}
        </td></tr>
        <tr><td>Submitted</td><td>{{ $ticket->created_at->format('d M Y, H:i') }}</td></tr>
    </table>
</div>

<div class="info-box" style="margin-top:0;">
    <strong style="font-size:.85rem; color:#6B7280; display:block; margin-bottom:8px;">MESSAGE</strong>
    <p style="margin:0; color:#374151; font-size:.9rem; line-height:1.6;">{{ $ticket->description }}</p>
</div>

<p style="text-align:center; margin:28px 0 16px;">
    <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn">
        View &amp; Reply to Ticket →
    </a>
</p>
@endsection
