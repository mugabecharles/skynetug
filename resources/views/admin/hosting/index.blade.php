@extends('layouts.dashboard')
@section('page_title', 'Hosting Accounts')

@section('content')
<div class="bg-white rounded-3 border p-3 mb-4">
    <form method="GET" class="row g-2">
        <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Domain or username…" value="{{ request('search') }}"></div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                @foreach(['active','pending','suspended','terminated'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-sky btn-sm w-100">Filter</button></div>
        <div class="col-md-2"><a href="{{ route('admin.hosting.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a></div>
    </form>
</div>

<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light">
                <tr><th>Domain</th><th>Username</th><th>Customer</th><th>Package</th><th>Server</th><th>Status</th><th>Expiry</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($accounts as $acc)
                <tr>
                    <td class="fw-semibold">{{ $acc->domain }}</td>
                    <td class="font-monospace small">{{ $acc->username }}</td>
                    <td>{{ $acc->user->name }}</td>
                    <td class="small text-muted">{{ $acc->hostingPackage?->name ?? '—' }}</td>
                    <td class="small text-muted">{{ $acc->server?->name ?? '—' }}</td>
                    <td>
                        @php $c=match($acc->status){'active'=>'success','pending'=>'warning','suspended'=>'danger','terminated'=>'secondary',default=>'secondary'}; @endphp
                        <span class="badge bg-{{ $c }}-subtle text-{{ $c }} rounded-pill">{{ ucfirst($acc->status) }}</span>
                    </td>
                    <td class="small {{ $acc->next_due_date && \Carbon\Carbon::parse($acc->next_due_date)->isPast() ? 'text-danger' : 'text-muted' }}">
                        {{ $acc->next_due_date ? \Carbon\Carbon::parse($acc->next_due_date)->format('d M Y') : '—' }}
                    </td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.hosting.show',$acc->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;font-size:.72rem;">View</a>
                        @if($acc->status==='active')
                        <form method="POST" action="{{ route('admin.hosting.suspend',$acc->id) }}" onsubmit="return confirm('Suspend this account?')">
                            @csrf <button class="btn btn-sm btn-outline-warning" style="border-radius:6px;font-size:.72rem;">Suspend</button>
                        </form>
                        @elseif($acc->status==='suspended')
                        <form method="POST" action="{{ route('admin.hosting.unsuspend',$acc->id) }}">
                            @csrf <button class="btn btn-sm btn-outline-success" style="border-radius:6px;font-size:.72rem;">Unsuspend</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No hosting accounts found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $accounts->links() }}</div>
</div>
@endsection
