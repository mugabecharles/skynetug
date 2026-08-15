@extends('layouts.dashboard')
@section('page_title', 'Announcements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="fw-bold mb-0">Announcements</h5><p class="text-muted small mb-0">Post platform-wide notices to customers</p></div>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-sky btn-sm"><i class="bi bi-plus me-1"></i>New Announcement</a>
</div>

<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light">
                <tr><th>Title</th><th>Status</th><th>Published</th><th>Created By</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($announcements as $ann)
                <tr>
                    <td class="fw-semibold">{{ $ann->title }}</td>
                    <td>
                        @php $c = match($ann->status) { 'published'=>'success','draft'=>'warning','archived'=>'secondary', default=>'secondary' }; @endphp
                        <span class="badge bg-{{ $c }}-subtle text-{{ $c }} rounded-pill">{{ ucfirst($ann->status) }}</span>
                    </td>
                    <td class="small text-muted">{{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('d M Y H:i') : '—' }}</td>
                    <td class="small">{{ $ann->createdBy?->name ?? '—' }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.announcements.edit', $ann->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;font-size:.75rem;">Edit</a>
                        <form method="POST" action="{{ route('admin.announcements.destroy', $ann->id) }}" onsubmit="return confirm('Delete this announcement?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;font-size:.75rem;">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">No announcements yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $announcements->links() }}</div>
</div>
@endsection
