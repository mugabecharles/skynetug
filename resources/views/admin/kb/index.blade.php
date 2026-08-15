@extends('layouts.dashboard')
@section('page_title', 'Knowledge Base')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="fw-bold mb-0">Knowledge Base</h5><p class="text-muted small mb-0">Manage help articles for customers</p></div>
    <a href="{{ route('admin.kb.create') }}" class="btn btn-sky btn-sm"><i class="bi bi-plus me-1"></i>New Article</a>
</div>

<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light">
                <tr><th>Title</th><th>Category</th><th>Views</th><th>Status</th><th>Author</th><th>Updated</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr>
                    <td class="fw-semibold">{{ $article->title }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $article->category }}</span></td>
                    <td>{{ number_format($article->views) }}</td>
                    <td>
                        @if($article->status === 'published')
                            <span class="badge bg-success-subtle text-success rounded-pill">Published</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning rounded-pill">Draft</span>
                        @endif
                    </td>
                    <td class="small">{{ $article->createdBy?->name ?? '—' }}</td>
                    <td class="small text-muted">{{ $article->updated_at->format('d M Y') }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.kb.edit', $article->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;font-size:.75rem;">Edit</a>
                        <a href="{{ route('kb.show', $article->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="border-radius:6px;font-size:.75rem;">View</a>
                        <form method="POST" action="{{ route('admin.kb.destroy', $article->id) }}" onsubmit="return confirm('Delete this article?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;font-size:.75rem;">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No articles yet. <a href="{{ route('admin.kb.create') }}">Add one</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $articles->links() }}</div>
</div>
@endsection
