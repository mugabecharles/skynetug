@extends('layouts.dashboard')
@section('page_title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="mb-3">
    <a href="{{ route('dashboard.tickets.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
        <i class="bi bi-arrow-left me-1"></i>Back to Tickets
    </a>
</div>

<div class="row g-4">
    {{-- Thread --}}
    <div class="col-lg-8">
        {{-- Original Ticket --}}
        <div class="bg-white rounded-3 border p-4 mb-3">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="fw-bold mb-1">{{ $ticket->subject }}</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @php $cc = match($ticket->category) { 'technical'=>'primary','billing'=>'warning','sales'=>'success','general'=>'secondary', default=>'secondary' }; @endphp
                        @php $pc = match($ticket->priority) { 'urgent'=>'danger','high'=>'warning','medium'=>'primary','low'=>'secondary', default=>'secondary' }; @endphp
                        @php $sc = match($ticket->status) { 'open'=>'success','in_progress'=>'primary','waiting_reply'=>'warning','resolved'=>'secondary','closed'=>'secondary', default=>'secondary' }; @endphp
                        <span class="badge bg-{{ $cc }}-subtle text-{{ $cc }} rounded-pill small">{{ ucfirst($ticket->category) }}</span>
                        <span class="badge bg-{{ $pc }}-subtle text-{{ $pc }} rounded-pill small">{{ ucfirst($ticket->priority) }} Priority</span>
                        <span class="badge bg-{{ $sc }}-subtle text-{{ $sc }} rounded-pill small">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span>
                    </div>
                </div>
                <span class="text-muted small">{{ $ticket->created_at->format('d M Y H:i') }}</span>
            </div>

            <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #e8ecf0;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:32px;height:32px;background:#0066FF;color:#fff;font-size:0.75rem;font-weight:700;">
                        {{ strtoupper(substr(auth()->user()->name,0,2)) }}
                    </div>
                    <span class="fw-semibold small">{{ auth()->user()->name }}</span>
                    <span class="text-muted small ms-auto">{{ $ticket->created_at->diffForHumans() }}</span>
                </div>
                <p class="mb-0 small" style="white-space:pre-line;line-height:1.7;">{{ $ticket->description }}</p>
            </div>
        </div>

        {{-- Replies --}}
        @foreach($ticket->replies as $reply)
        <div class="mb-3">
            @if($reply->is_staff_reply)
            <div class="bg-white rounded-3 border border-primary p-4" style="border-color:#0066FF !important;">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:32px;height:32px;background:#0066FF;color:#fff;font-size:0.7rem;font-weight:700;">
                        SN
                    </div>
                    <div>
                        <span class="fw-semibold small">SkyNetug Support</span>
                        <span class="badge bg-primary ms-1" style="font-size:0.65rem;">Staff</span>
                    </div>
                    <span class="text-muted small ms-auto">{{ $reply->created_at->diffForHumans() }}</span>
                </div>
                <p class="mb-0 small" style="white-space:pre-line;line-height:1.7;">{{ $reply->message }}</p>
            </div>
            @else
            <div class="bg-white rounded-3 border p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:32px;height:32px;background:#6b7280;color:#fff;font-size:0.75rem;font-weight:700;">
                        {{ strtoupper(substr($reply->user->name,0,2)) }}
                    </div>
                    <span class="fw-semibold small">{{ $reply->user->name }}</span>
                    <span class="text-muted small ms-auto">{{ $reply->created_at->diffForHumans() }}</span>
                </div>
                <p class="mb-0 small" style="white-space:pre-line;line-height:1.7;">{{ $reply->message }}</p>
            </div>
            @endif

            @if($reply->attachments->isNotEmpty())
            <div class="mt-1 ms-5">
                @foreach($reply->attachments as $att)
                <a href="{{ asset('storage/' . $att->filename) }}" class="btn btn-sm btn-outline-secondary me-1" style="font-size:0.75rem;border-radius:6px;" target="_blank">
                    <i class="bi bi-paperclip me-1"></i>{{ $att->original_name }}
                </a>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach

        {{-- Reply Form --}}
        @if(!in_array($ticket->status, ['resolved','closed']))
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Add Reply</h6>
            <form method="POST" action="{{ route('dashboard.tickets.reply', $ticket->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror"
                        placeholder="Type your reply here..." required>{{ old('message') }}</textarea>
                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <input type="file" name="attachments[]" class="form-control form-control-sm" multiple accept="image/*,.pdf,.txt,.log,.zip">
                    <div class="form-text">Optional: attach files (max 10 MB each)</div>
                </div>
                <button type="submit" class="btn btn-sky">
                    <i class="bi bi-send me-2"></i>Send Reply
                </button>
            </form>
        </div>
        @else
        <div class="alert alert-secondary text-center">
            <i class="bi bi-check-circle me-2"></i>This ticket is {{ $ticket->status }}. To reopen, please create a new ticket.
        </div>
        @endif
    </div>

    {{-- Ticket Info Sidebar --}}
    <div class="col-lg-4">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Ticket Information</h6>
            <dl class="row small mb-0">
                <dt class="col-5 text-muted">Ticket #</dt>
                <dd class="col-7 fw-semibold">{{ $ticket->ticket_number }}</dd>
                <dt class="col-5 text-muted">Opened</dt>
                <dd class="col-7">{{ $ticket->created_at->format('d M Y') }}</dd>
                <dt class="col-5 text-muted">Last Reply</dt>
                <dd class="col-7">{{ $ticket->last_reply_at ? \Carbon\Carbon::parse($ticket->last_reply_at)->diffForHumans() : 'No replies yet' }}</dd>
                <dt class="col-5 text-muted">Assigned To</dt>
                <dd class="col-7">{{ $ticket->assignedTo?->name ?? 'Unassigned' }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
