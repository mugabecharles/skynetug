@extends('layouts.dashboard')
@section('page_title', 'Open New Ticket')

@section('content')
<div class="mb-4">
    <a href="{{ route('dashboard.tickets.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
        <i class="bi bi-arrow-left me-1"></i>Back to Tickets
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-4">New Support Ticket</h6>

            <form method="POST" action="{{ route('dashboard.tickets.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                        value="{{ old('subject') }}" placeholder="Briefly describe your issue" required>
                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Select category...</option>
                            <option value="technical" {{ old('category')=='technical' ? 'selected' : '' }}>🔧 Technical</option>
                            <option value="billing"   {{ old('category')=='billing'   ? 'selected' : '' }}>💳 Billing</option>
                            <option value="sales"     {{ old('category')=='sales'     ? 'selected' : '' }}>🛒 Sales</option>
                            <option value="general"   {{ old('category')=='general'   ? 'selected' : '' }}>💬 General</option>
                        </select>
                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Priority <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                            <option value="low"    {{ old('priority','medium')=='low'    ? 'selected' : '' }}>🟢 Low</option>
                            <option value="medium" {{ old('priority','medium')=='medium' ? 'selected' : '' }}>🟡 Medium</option>
                            <option value="high"   {{ old('priority')=='high'   ? 'selected' : '' }}>🟠 High</option>
                            <option value="urgent" {{ old('priority')=='urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                        </select>
                        @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Description <span class="text-danger">*</span></label>
                    <textarea name="description" rows="7" class="form-control @error('description') is-invalid @enderror"
                        placeholder="Please describe your issue in detail. Include error messages, steps to reproduce, and any other relevant information." required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Attachments <span class="text-muted">(optional, max 10 MB each)</span></label>
                    <input type="file" name="attachments[]" class="form-control @error('attachments.*') is-invalid @enderror"
                        multiple accept="image/*,.pdf,.txt,.log,.zip">
                    @error('attachments.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">You can attach screenshots, logs, or error reports.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sky px-4">
                        <i class="bi bi-send me-2"></i>Submit Ticket
                    </button>
                    <a href="{{ route('dashboard.tickets.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="bg-light rounded-3 border p-4 mb-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-book me-2 text-sky"></i>Before You Submit</h6>
            <p class="small text-muted mb-3">Check our Knowledge Base — your answer might already be there:</p>
            <a href="{{ route('kb.index') }}" class="btn btn-outline-primary btn-sm w-100" target="_blank">
                Browse Knowledge Base <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Response Times</h6>
            <ul class="list-unstyled small text-muted mb-0">
                <li class="mb-2"><span class="badge bg-danger me-2">Urgent</span>Within 1 hour</li>
                <li class="mb-2"><span class="badge bg-warning me-2">High</span>Within 4 hours</li>
                <li class="mb-2"><span class="badge bg-primary me-2">Medium</span>Within 24 hours</li>
                <li><span class="badge bg-secondary me-2">Low</span>Within 48 hours</li>
            </ul>
        </div>
    </div>
</div>
@endsection
