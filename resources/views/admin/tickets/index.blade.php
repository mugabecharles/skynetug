@extends('layouts.dashboard')
@section('page_title', 'Support Tickets')

@section('content')
<div class="bg-white rounded-3 border p-3 mb-4">
    <form method="GET" class="row g-2">
        <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search…" value="{{ request('search') }}"></div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                @foreach(['open','in_progress','waiting_reply','resolved','closed'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="priority" class="form-select form-select-sm">
                <option value="">All Priority</option>
                @foreach(['low','medium','high','urgent'] as $p)
                <option value="{{ $p }}" {{ request('priority')==$p?'selected':'' }}>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-sky btn-sm w-100">Filter</button></div>
        <div class="col-md-2"><a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a></div>
    </form>
</div>

<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light">
                <tr><th>Ticket #</th><th>Subject</th><th>Customer</th><th>Category</th><th>Priority</th><th>Status</th><th>Assigned To</th><th>Updated</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td class="fw-semibold text-sky">{{ $ticket->ticket_number }}</td>
                    <td>{{ Str::limit($ticket->subject,35) }}</td>
                    <td>{{ $ticket->user->name }}</td>
                    <td><span class="badge bg-light text-dark border">{{ ucfirst($ticket->category) }}</span></td>
                    <td>
                        @php $pc=match($ticket->priority){'urgent'=>'danger','high'=>'warning','medium'=>'primary','low'=>'secondary',default=>'secondary'}; @endphp
                        <span class="badge bg-{{ $pc }}-subtle text-{{ $pc }} rounded-pill">{{ ucfirst($ticket->priority) }}</span>
                    </td>
                    <td>
                        @php $sc=match($ticket->status){'open'=>'success','in_progress'=>'primary','waiting_reply'=>'warning','resolved'=>'secondary','closed'=>'secondary',default=>'secondary'}; @endphp
                        <span class="badge bg-{{ $sc }}-subtle text-{{ $sc }} rounded-pill">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span>
                    </td>
                    <td class="small text-muted">{{ $ticket->assignedTo?->name ?? 'Unassigned' }}</td>
                    <td class="small text-muted">{{ $ticket->updated_at->diffForHumans() }}</td>
                    <td><a href="{{ route('admin.tickets.show',$ticket->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;font-size:.75rem;">View</a></td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-4 text-muted">No tickets found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $tickets->links() }}</div>
</div>
@endsection
