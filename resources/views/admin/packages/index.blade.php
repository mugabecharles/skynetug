@extends('layouts.dashboard')
@section('page_title', 'Hosting Packages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="fw-bold mb-0">Packages</h5><p class="text-muted small mb-0">Manage hosting plans and services</p></div>
    <a href="{{ route('admin.packages.create') }}" class="btn btn-sky btn-sm"><i class="bi bi-plus me-1"></i>Add Package</a>
</div>

<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light">
                <tr><th>Name</th><th>Type</th><th>Monthly</th><th>Yearly</th><th>Disk</th><th>Featured</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($packages as $pkg)
                <tr>
                    <td class="fw-semibold">{{ $pkg->name }}</td>
                    <td><span class="badge bg-light text-dark border">{{ ucfirst($pkg->type) }}</span></td>
                    <td>$ {{ number_format($pkg->price_monthly, 2) }}</td>
                    <td>$ {{ number_format($pkg->price_yearly, 2) }}</td>
                    <td>{{ $pkg->disk_space_mb == 0 ? 'Unlimited' : number_format($pkg->disk_space_mb / 1024, 0).' GB' }}</td>
                    <td>{!! $pkg->is_featured ? '<i class="bi bi-star-fill text-warning"></i>' : '—' !!}</td>
                    <td>
                        @if($pkg->is_active)<span class="badge bg-success-subtle text-success">Active</span>
                        @else<span class="badge bg-secondary-subtle text-secondary">Inactive</span>@endif
                    </td>
                    <td>
                        <a href="{{ route('admin.packages.edit', $pkg->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;font-size:.75rem;">Edit</a>
                        <form method="POST" action="{{ route('admin.packages.destroy', $pkg->id) }}" class="d-inline" onsubmit="return confirm('Delete this package?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;font-size:.75rem;">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No packages yet. <a href="{{ route('admin.packages.create') }}">Add one</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $packages->links() }}</div>
</div>
@endsection
