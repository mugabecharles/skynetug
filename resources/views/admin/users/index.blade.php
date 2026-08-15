@extends('layouts.dashboard')
@section('page_title', 'User Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="fw-bold mb-0">Users</h5><p class="text-muted small mb-0">Manage all customers and staff accounts</p></div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-sky btn-sm"><i class="bi bi-plus me-1"></i>Add User</a>
</div>

<div class="bg-white rounded-3 border p-3 mb-4">
    <form method="GET" class="row g-2">
        <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or email…" value="{{ request('search') }}"></div>
        <div class="col-md-2">
            <select name="role" class="form-select form-select-sm">
                <option value="">All Roles</option>
                @foreach(['customer','super_admin','billing_manager','technical_admin','support_agent','sales_manager'] as $r)
                <option value="{{ $r }}" {{ request('role')==$r?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-sky btn-sm w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
        <div class="col-md-2"><a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a></div>
    </form>
</div>

<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light">
                <tr><th>Name</th><th>Email</th><th>Role</th><th>Country</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:32px;height:32px;background:#0066FF18;color:#0066FF;font-weight:700;font-size:.75rem;">
                                {{ strtoupper(substr($user->name,0,2)) }}
                            </div>
                            <span class="fw-semibold">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>
                        @php $rc=match($user->role){'super_admin'=>'danger','billing_manager'=>'warning','technical_admin'=>'info','support_agent'=>'primary','sales_manager'=>'success',default=>'secondary'}; @endphp
                        <span class="badge bg-{{ $rc }}-subtle text-{{ $rc }} rounded-pill">{{ ucfirst(str_replace('_',' ',$user->role)) }}</span>
                    </td>
                    <td>{{ $user->country ?? '—' }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success-subtle text-success rounded-pill">Active</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger rounded-pill">Disabled</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;font-size:.75rem;">Edit</a>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:6px;font-size:.75rem;">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $users->links() }}</div>
</div>
@endsection
