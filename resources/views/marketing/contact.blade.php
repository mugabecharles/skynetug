@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<div style="background:linear-gradient(135deg,#0A0F1E,#0A0F1E);padding:3.5rem 0 2.5rem;">
    <div class="container text-center">
        <h1 class="fw-bold text-white mb-2">Get in Touch</h1>
        <p class="text-white-50">Our team is ready to help you. We respond within 24 hours.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5 justify-content-center">
        {{-- Contact Form --}}
        <div class="col-lg-7">
            <div class="bg-white rounded-3 border p-4">
                <h5 class="fw-bold mb-4">Send Us a Message</h5>

                @if(session('success'))
                    <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Your Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="John Mukasa" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="you@example.com" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                value="{{ old('subject') }}" placeholder="How can we help?" required>
                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Message <span class="text-danger">*</span></label>
                            <textarea name="message" rows="6" class="form-control @error('message') is-invalid @enderror"
                                placeholder="Describe your query in detail..." required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sky px-5 mt-4">
                        <i class="bi bi-send me-2"></i>Send Message
                    </button>
                </form>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="col-lg-4">
            <div class="mb-4">
                <h5 class="fw-bold mb-3">Contact Information</h5>
                @foreach([
                    ['icon'=>'bi-geo-alt','label'=>'Office Address','val'=>'Kampala, Uganda'],
                    ['icon'=>'bi-envelope','label'=>'Email Support','val'=>'support@skynetug.com'],
                    ['icon'=>'bi-phone','label'=>'Phone / WhatsApp','val'=>'+256 700 000 000'],
                    ['icon'=>'bi-clock','label'=>'Support Hours','val'=>'24/7 — always open'],
                ] as $info)
                <div class="d-flex gap-3 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:44px;height:44px;background:#f0f4ff;">
                        <i class="bi {{ $info['icon'] }} text-sky"></i>
                    </div>
                    <div>
                        <div class="text-muted small">{{ $info['label'] }}</div>
                        <div class="fw-semibold">{{ $info['val'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-primary rounded-3 p-4 text-white text-center">
                <i class="bi bi-headset fs-2 d-block mb-2"></i>
                <h6 class="fw-bold">Need Immediate Help?</h6>
                <p class="small opacity-75 mb-3">Existing customers can open a priority support ticket from the dashboard.</p>
                @auth
                    <a href="{{ route('dashboard.tickets.create') }}" class="btn btn-light btn-sm px-4">Open Ticket</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light btn-sm px-4">Sign In</a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection


