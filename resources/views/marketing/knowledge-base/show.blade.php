@extends('layouts.app')
@section('title', $article->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <a href="{{ route('kb.index') }}" class="btn btn-sm btn-outline-secondary mb-4" style="border-radius:8px;">
                <i class="bi bi-arrow-left me-1"></i>Back to Knowledge Base
            </a>
            <div class="bg-white rounded-3 border p-5">
                <span class="badge bg-primary-subtle text-primary rounded-pill mb-3">{{ $article->category }}</span>
                <h1 class="fw-bold mb-3" style="font-size:1.75rem;">{{ $article->title }}</h1>
                <p class="text-muted small mb-4">
                    <i class="bi bi-clock me-1"></i>Last updated {{ $article->updated_at->format('d M Y') }}
                    &nbsp;·&nbsp; <i class="bi bi-eye me-1"></i>{{ number_format($article->views) }} views
                </p>
                <div class="article-content" style="line-height:1.8;color:#374151;">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </div>

            <div class="bg-light rounded-3 p-4 mt-4 text-center">
                <h6 class="fw-bold">Was this article helpful?</h6>
                <p class="text-muted small mb-3">If you couldn't find what you needed, open a support ticket.</p>
                <a href="{{ route('dashboard.tickets.create') }}" class="btn btn-sky btn-sm px-4">Open Support Ticket</a>
            </div>
        </div>
    </div>
</div>
@endsection

