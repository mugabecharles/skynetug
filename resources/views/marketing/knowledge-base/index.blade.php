@extends('layouts.app')
@section('title', 'Knowledge Base')

@section('content')
<div style="background:linear-gradient(135deg,#0A0F1E,#0A0F1E);padding:3.5rem 0 2.5rem;">
    <div class="container text-center">
        <h1 class="fw-bold text-white mb-3">Knowledge Base</h1>
        <p class="text-white-50 mb-4">Find answers to common questions about hosting, domains, and billing.</p>
        <div class="mx-auto" style="max-width:500px;">
            <div class="input-group">
                <input type="text" id="kbSearch" class="form-control" placeholder="Search articles..."
                    style="border-radius:10px 0 0 10px;background:rgba(255,255,255,.1);color:#fff;border-color:rgba(255,255,255,.2);">
                <button class="btn btn-sky px-4" style="border-radius:0 10px 10px 0;">Search</button>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    @if(isset($articles) && $articles->isNotEmpty())
    <div class="row g-3">
        @foreach($articles as $article)
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('kb.show', $article->slug) }}" class="text-decoration-none">
                <div class="bg-white rounded-3 border p-4 h-100" style="transition:all .2s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow=''">
                    <span class="badge bg-primary-subtle text-primary rounded-pill mb-2 small">{{ $article->category }}</span>
                    <h6 class="fw-bold text-dark mb-2">{{ $article->title }}</h6>
                    <p class="text-muted small mb-0">{{ Str::limit(strip_tags($article->content), 100) }}</p>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    {{ $articles->links() }}
    @else
    <div class="row g-4 justify-content-center">
        @foreach(['Getting Started','Hosting & cPanel','Domain Management','Billing & Payments','Email Hosting','Security & SSL'] as $cat)
        <div class="col-md-4 col-lg-3 text-center">
            <div class="p-4 bg-light rounded-3">
                <i class="bi bi-book fs-2 text-sky d-block mb-2"></i>
                <h6 class="fw-bold">{{ $cat }}</h6>
                <p class="text-muted small">Articles on {{ strtolower($cat) }}</p>
                <a href="{{ route('dashboard.tickets.create') }}" class="btn btn-sm btn-outline-primary">Ask Support</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection


