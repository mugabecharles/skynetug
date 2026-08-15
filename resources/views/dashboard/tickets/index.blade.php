@extends('layouts.dashboard')
@section('page_title', 'Support Tickets')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Support Tickets</h5>
        <p class="text-muted small mb-0">Get help from our team</p>
    </div>
    <a href="{{ route('dashboard.tickets.create') }}" class="btn btn-sky btn-sm">
        <i class="bi bi-plus me-1"></i>New Ticket
    </a>
</div>

<div class="bg-white rounded-3 border">
    @if($tickets->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-headset display-5 d-block mb-3 opacity-30"></i>
            <h6>No tickets yet</h6>
            <p class="small">Need help? Open a support ticket and our team will respond quickly.</p>
            <a href="{{ route('dashboard.tickets.create') }}" class="btn btn-sky btn-sm">Open a Ticket</a>
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:0.875rem;">
            <thead class="table-light">
                <tr>
                    <th>Ticket #</th>
                    <th>Subject</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                <tr>
                    <td class="fw-semibold text-sky">{{ $ticket->ticket_number }}</td>
                    <td>{{ Str::limit($ticket->subject, 40) }}</td>
                    <td>
                        @php $cc = match($ticket->category) { 'technical'=>'primary','billing'=>'warning','sales'=>'success','general'=>'secondary', default=>'secondary' }; @endphp
                        <span class="badge bg-{{ $cc }}-subtle text-{{ $cc }} rounded-pill">{{ ucfirst($ticket->category) }}</span>
                    </td>
                    <td>
                        @php $pc = match($ticket->priority) { 'urgent'=>'danger','high'=>'warning','medium'=>'primary','low'=>'secondary', default=>'secondary' }; @endphp
                        <span class="badge bg-{{ $pc }}-subtle text-{{ $pc }} rounded-pill">{{ ucfirst($ticket->priority) }}</span>
                    </td>
                    <td>
                        @php $sc = match($ticket->status) { 'open'=>'success','in_progress'=>'primary','waiting_reply'=>'warning','resolved'=>'secondary','closed'=>'secondary', default=>'secondary' }; @endphp
                        <span class="badge bg-{{ $sc }}-subtle text-{{ $sc }} rounded-pill">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span>
                    </td>
                    <td class="text-muted small">{{ $ticket->updated_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('dashboard.tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;font-size:0.75rem;">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $tickets->links() }}</div>
    @endif
</div>
@endsection
