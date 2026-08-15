@extends('layouts.dashboard')
@section('page_title', isset($kb) ? 'Edit Article' : 'New Article')

@section('content')
<div class="mb-4"><a href="{{ route('admin.kb.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a></div>

<div class="row justify-content-center"><div class="col-lg-9">
<div class="bg-white rounded-3 border p-4">
    <h6 class="fw-bold mb-4">{{ isset($kb) ? 'Edit' : 'Create' }} Knowledge Base Article</h6>
    <form method="POST" action="{{ isset($kb) ? route('admin.kb.update',$kb->id) : route('admin.kb.store') }}">
        @csrf @if(isset($kb)) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold small">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                    value="{{ old('title', $kb->title ?? '') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Category <span class="text-danger">*</span></label>
                <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
                    value="{{ old('category', $kb->category ?? '') }}" list="categories" required placeholder="e.g. Getting Started">
                <datalist id="categories">
                    @foreach(['Getting Started','Hosting & cPanel','Domain Management','Billing & Payments','Email Hosting','Security & SSL','Troubleshooting'] as $cat)
                    <option value="{{ $cat }}">
                    @endforeach
                </datalist>
                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold small">Content <span class="text-danger">*</span></label>
                <textarea name="content" rows="14" class="form-control @error('content') is-invalid @enderror" required
                    placeholder="Write your article content here. You can use plain text or basic HTML.">{{ old('content', $kb->content ?? '') }}</textarea>
                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold small">Status</label>
                <div class="d-flex gap-4">
                    @foreach(['draft'=>'Save as Draft','published'=>'Publish'] as $val=>$label)
                    <div class="form-check">
                        <input type="radio" name="status" value="{{ $val }}" class="form-check-input" id="s{{ $val }}"
                            {{ old('status', $kb->status ?? 'draft') == $val ? 'checked' : '' }}>
                        <label class="form-check-label small" for="s{{ $val }}">{{ $label }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-sky px-4"><i class="bi bi-save me-2"></i>{{ isset($kb) ? 'Save Changes' : 'Save Article' }}</button>
            <a href="{{ route('admin.kb.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
</div></div>
@endsection
