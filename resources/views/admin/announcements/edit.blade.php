@extends('layouts.dashboard')
@section('page_title', isset($announcement) ? 'Edit Announcement' : 'New Announcement')

@section('content')
<div class="mb-4"><a href="{{ route('admin.announcements.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a></div>

<div class="row justify-content-center"><div class="col-lg-8">
<div class="bg-white rounded-3 border p-4">
    <h6 class="fw-bold mb-4">{{ isset($announcement) ? 'Edit' : 'Create' }} Announcement</h6>
    <form method="POST" action="{{ isset($announcement) ? route('admin.announcements.update',$announcement->id) : route('admin.announcements.store') }}">
        @csrf @if(isset($announcement)) @method('PUT') @endif
        <div class="mb-3">
            <label class="form-label fw-semibold small">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $announcement->title ?? '') }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold small">Content <span class="text-danger">*</span></label>
            <textarea name="content" rows="8" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $announcement->content ?? '') }}</textarea>
            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold small">Status</label>
            <div class="d-flex gap-4">
                @foreach(['draft'=>'Save as Draft','published'=>'Publish Now','archived'=>'Archive'] as $val => $label)
                <div class="form-check">
                    <input type="radio" name="status" value="{{ $val }}" class="form-check-input" id="status_{{ $val }}"
                        {{ old('status', $announcement->status ?? 'draft') == $val ? 'checked' : '' }}>
                    <label class="form-check-label small" for="status_{{ $val }}">{{ $label }}</label>
                </div>
                @endforeach
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-sky px-4"><i class="bi bi-megaphone me-2"></i>{{ isset($announcement) ? 'Save Changes' : 'Post Announcement' }}</button>
            <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
</div></div>
@endsection
