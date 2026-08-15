@extends('layouts.dashboard')
@section('page_title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="mb-3 d-flex gap-2">
    <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Original message --}}
        <div class="bg-white rounded-3 border p-4 mb-3">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h6 class="fw-bold mb-0">{{ $ticket->subject }}</h6>
                <span class="text-muted small">{{ $ticket->created_at->format('d M Y H:i') }}</span>
            </div>
            <div class="p-3 bg-light rounded-3">
                <div class="fw-semibold small mb-2">{{ $ticket->user->name }} <span class="text-muted fw-normal">(Customer)</span></div>
                <p class="mb-0 small" style="white-space:pre-line;">{{ $ticket->description }}</p>
            </div>
        </div>

        {{-- Replies --}}
        @foreach($ticket->replies as $reply)
        <div class="mb-3">
            <div class="p-4 rounded-3 border {{ $reply->is_staff_reply ? 'bg-primary bg-opacity-10 border-primary' : 'bg-white' }}">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="fw-semibold small">{{ $reply->user->name }}</span>
                    @if($reply->is_staff_reply)<span class="badge bg-primary ms-1" style="font-size:.65rem;">Staff</span>@endif
                    <span class="text-muted small ms-auto">{{ $reply->created_at->diffForHumans() }}</span>
                </div>
                <p class="mb-0 small" style="white-space:pre-line;">{{ $reply->message }}</p>
            </div>
        </div>
        @endforeach

        {{-- Reply Form --}}
        @if(!in_array($ticket->status,['closed']))
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Staff Reply</h6>
            <form method="POST" action="{{ route('admin.tickets.reply',$ticket->id) }}">
                @csrf
                <textarea name="message" rows="5" class="form-control mb-3" placeholder="Type your reply…" required></textarea>
                <div class="d-flex gap-2">
                    <button class="btn btn-sky"><i class="bi bi-send me-2"></i>Send Reply</button>
                    <form method="POST" action="{{ route('admin.tickets.close',$ticket->id) }}" class="d-inline">@csrf
                        <button class="btn btn-outline-secondary">Close Ticket</button>
                    </form>
                </div>
            </form>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="bg-white rounded-3 border p-4 mb-3">
            <h6 class="fw-bold mb-3">Ticket Details</h6>
            <dl class="row small mb-0">
                @php $sc=match($ticket->status){'open'=>'success','in_progress'=>'primary','waiting_reply'=>'warning','resolved'=>'secondary','closed'=>'secondary',default=>'secondary'}; @endphp
                @php $pc=match($ticket->priority){'urgent'=>'danger','high'=>'warning','medium'=>'primary','low'=>'secondary',default=>'secondary'}; @endphp
                <dt class="col-5 text-muted">Status</dt>
                <dd class="col-7"><span class="badge bg-{{ $sc }}-subtle text-{{ $sc }}">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span></dd>
                <dt class="col-5 text-muted">Priority</dt>
                <dd class="col-7"><span class="badge bg-{{ $pc }}-subtle text-{{ $pc }}">{{ ucfirst($ticket->priority) }}</span></dd>
                <dt class="col-5 text-muted">Category</dt>
                <dd class="col-7">{{ ucfirst($ticket->category) }}</dd>
                <dt class="col-5 text-muted">Customer</dt>
                <dd class="col-7"><a href="{{ route('admin.users.show',$ticket->user_id) }}">{{ $ticket->user->name }}</a></dd>
                <dt class="col-5 text-muted">Assigned</dt>
                <dd class="col-7">{{ $ticket->assignedTo?->name ?? 'Unassigned' }}</dd>
            </dl>
        </div>
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Actions</h6>
            <form method="POST" action="{{ route('admin.tickets.assign',$ticket->id) }}" class="mb-2">
                @csrf
                <div class="input-group input-group-sm">
                    <select name="user_id" class="form-select">
                        <option value="">Assign to…</option>
                        @foreach(\App\Models\User::whereIn('role',['support_agent','technical_admin','super_admin'])->get() as $agent)
                        <option value="{{ $agent->id }}" {{ $ticket->assigned_to==$agent->id?'selected':'' }}>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sky">Assign</button>
                </div>
            </form>
            <form method="POST" action="{{ route('admin.tickets.escalate',$ticket->id) }}">
                @csrf
                <button class="btn btn-outline-warning btn-sm w-100">Escalate Ticket</button>
            </form>
        </div>
    </div>
</div>
@endsection
