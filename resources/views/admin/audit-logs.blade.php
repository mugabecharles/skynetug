@extends('layouts.dashboard')
@section('page_title', 'Audit Logs')

@section('content')
<div class="bg-white rounded-3 border p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Action Type</label>
            <select name="action_type" class="form-select form-select-sm">
                <option value="">All Actions</option>
                @foreach($actionTypes as $type)
                <option value="{{ $type }}" {{ request('action_type')==$type ? 'selected':'' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">From Date</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">To Date</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-sky btn-sm w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.audit-logs') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:0.825rem;">
            <thead class="table-light">
                <tr>
                    <th>Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Resource</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-muted small text-nowrap">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                    <td>{{ $log->user?->name ?? '<span class="text-muted">System</span>' }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $log->action_type }}</span></td>
                    <td class="small">{{ $log->resource_type }}{{ $log->resource_id ? ' #'.$log->resource_id : '' }}</td>
                    <td class="small text-muted">{{ Str::limit($log->description, 80) }}</td>
                    <td class="font-monospace small">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No audit logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $logs->links() }}</div>
</div>
@endsection
