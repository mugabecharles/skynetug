@extends('layouts.app')
@section('title', 'Register Domain')

@section('content')
<div style="background:linear-gradient(135deg,#0A0F1E,#0A0F1E);padding:2.5rem 0 2rem;">
    <div class="container">
        <h4 class="fw-bold text-white mb-0">
            <i class="bi bi-link-45deg me-2"></i>Register Domain
        </h4>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center g-5">

        <div class="col-lg-7">
            <form method="POST" action="{{ route('order.checkout') }}">
                @csrf

                <div class="bg-white rounded-3 border p-4 mb-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-globe me-2 text-sky"></i>Domain to Register</h6>

                    @if($domain)
                    <div class="p-3 bg-success bg-opacity-10 border border-success rounded-3 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span class="fw-bold">{{ $domain }}</span>
                            <span class="badge bg-success-subtle text-success ms-auto">Available</span>
                        </div>
                    </div>
                    <input type="hidden" name="items[0][meta][domain]" value="{{ $domain }}">
                    @else
                    <input type="text" name="items[0][meta][domain]" class="form-control mb-2"
                        placeholder="yourdomain.com" required>
                    @endif

                    <input type="hidden" name="items[0][type]" value="domain">
                    <input type="hidden" name="items[0][name]" value="Domain Registration: {{ $domain ?? '' }}">
                    <input type="hidden" name="items[0][billing_cycle]" value="yearly">
                    <input type="hidden" name="items[0][price]" value="35000">
                    <input type="hidden" name="items[0][quantity]" value="1">
                </div>

                {{-- Registration period --}}
                <div class="bg-white rounded-3 border p-4 mb-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-calendar me-2 text-sky"></i>Registration Period</h6>
                    <select name="items[0][meta][years]" class="form-select">
                        <option value="1">1 Year — $ 35,000</option>
                        <option value="2">2 Years — $ 70,000</option>
                        <option value="3">3 Years — $ 105,000</option>
                    </select>
                </div>

                {{-- Contact info --}}
                <div class="bg-white rounded-3 border p-4 mb-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-person me-2 text-sky"></i>Registrant Contact</h6>
                    <p class="text-muted small mb-3">Your account details will be used. You can add WHOIS privacy to hide this information.</p>
                    <div class="form-check">
                        <input type="checkbox" name="items[0][meta][whois_privacy]" value="1" class="form-check-input" id="whoisPrivacy">
                        <label class="form-check-label small" for="whoisPrivacy">
                            <strong>Add WHOIS Privacy Protection</strong> — hide your personal details from public WHOIS
                            <span class="badge bg-success-subtle text-success ms-1">Free</span>
                        </label>
                    </div>
                </div>

                {{-- Coupon --}}
                <div class="bg-white rounded-3 border p-4 mb-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-tag me-2 text-sky"></i>Promo Code</h6>
                    <div class="input-group" style="max-width:320px;">
                        <input type="text" name="coupon_code" class="form-control form-control-sm" placeholder="Enter coupon code">
                        <button type="button" class="btn btn-outline-primary btn-sm">Apply</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-sky w-100 py-3 fw-bold fs-5">
                    <i class="bi bi-lock me-2"></i>Proceed to Payment
                </button>
                <p class="text-muted text-center small mt-2">
                    <i class="bi bi-shield-check me-1 text-success"></i>Domain registered instantly after payment confirmation.
                </p>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="bg-white rounded-3 border p-4" style="position:sticky;top:80px;">
                <h6 class="fw-bold mb-3">Order Summary</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ $domain ?? 'Domain' }}</span>
                    <span class="fw-bold text-sky">$ 35,000</span>
                </div>
                <div class="d-flex justify-content-between text-muted small mb-3">
                    <span>1 year registration</span>
                </div>
                <hr>
                <ul class="list-unstyled mb-3" style="font-size:.875rem;">
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Instant Registration</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Free DNS Management</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Free WHOIS Privacy</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Auto-renewal Option</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Domain Lock Included</li>
                </ul>
                <div class="fw-bold d-flex justify-content-between fs-5">
                    <span>Total</span>
                    <span class="text-sky">$ 35,000</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

