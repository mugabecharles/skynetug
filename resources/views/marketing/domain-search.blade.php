@extends('layouts.app')
@section('title', 'Domain Search Results')

@push('styles')
<style>
.search-hero { background:linear-gradient(135deg,#0A0F1E,#0D1433); padding:3rem 0 2.5rem; }
.result-card { border-radius:12px; padding:16px 20px; margin-bottom:10px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; transition:box-shadow .2s; }
.result-card.available { background:#fff; border:2px solid #00C896; box-shadow:0 2px 12px rgba(0,200,150,.1); }
.result-card.taken { background:#f8fafc; border:1.5px solid #e8ecf0; opacity:.7; }
.result-card.checking { background:#fff; border:1.5px solid #e8ecf0; opacity:.6; }
.domain-name { font-size:1.1rem; font-weight:700; color:#0A0F1E; }
.domain-price { font-size:1rem; font-weight:700; color:#0066FF; }
.badge-available { background:#ECFDF5; color:#065f46; border-radius:20px; padding:4px 14px; font-size:.8rem; font-weight:700; }
.badge-taken { background:#FEF2F2; color:#991b1b; border-radius:20px; padding:4px 14px; font-size:.8rem; font-weight:700; }
.badge-checking { background:#EFF6FF; color:#1e40af; border-radius:20px; padding:4px 14px; font-size:.8rem; font-weight:700; }
.btn-register { background:#0066FF; color:#fff; border:none; border-radius:8px; padding:9px 22px; font-weight:700; font-size:.875rem; text-decoration:none; transition:background .15s; cursor:pointer; }
.btn-register:hover { background:#0050CC; color:#fff; }
.btn-taken { background:#f3f4f6; color:#9ca3af; border:none; border-radius:8px; padding:9px 22px; font-weight:600; font-size:.875rem; cursor:not-allowed; }
</style>
@endpush

@section('content')

<div class="search-hero">
    <div class="container text-center">
        <h1 class="fw-bold text-white mb-3">Find Your Domain</h1>
        <div class="mx-auto" style="max-width:640px;">
            <div class="input-group input-group-lg">
                <input type="text" id="domainInput" class="form-control" value="{{ $query }}"
                    placeholder="Enter domain name e.g. skynetug"
                    style="border-radius:12px 0 0 12px;background:rgba(255,255,255,.1);color:#fff;border-color:rgba(255,255,255,.2);">
                <button class="btn" onclick="searchDomain()" id="searchBtn"
                    style="background:#0066FF;color:#fff;border-radius:0 12px 12px 0;padding:0 28px;font-weight:700;">
                    <i class="bi bi-search me-2"></i>Search
                </button>
            </div>
            <div style="color:rgba(255,255,255,.5);font-size:.82rem;margin-top:8px;">
                Enter a name without extension — we'll check all TLDs for you
            </div>
        </div>
    </div>
</div>

<div class="container py-5">

    {{-- Loading --}}
    <div id="loadingSection" style="display:none;text-align:center;padding:40px;">
        <div class="spinner-border text-primary mb-3" role="status" style="width:3rem;height:3rem;"></div>
        <p class="text-muted">Checking domain availability across all extensions...</p>
    </div>

    {{-- Results --}}
    <div id="resultsSection" style="display:none;">
        <h5 id="resultsTitle" class="fw-bold mb-4"></h5>

        <div class="row">
            <div class="col-lg-8">
                <h6 class="text-success fw-bold mb-3" id="availableHeader" style="display:none;">
                    <i class="bi bi-check-circle-fill me-2"></i>Available Domains
                </h6>
                <div id="availableList"></div>

                <h6 class="text-muted fw-bold mb-3 mt-4" id="takenHeader" style="display:none;">
                    <i class="bi bi-x-circle me-2"></i>Unavailable Domains
                </h6>
                <div id="takenList"></div>
            </div>

            <div class="col-lg-4">
                <div style="background:#f8fafc;border:1px solid #e8ecf0;border-radius:12px;padding:20px;position:sticky;top:80px;">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-sky"></i>Domain Tips</h6>
                    <ul class="list-unstyled" style="font-size:.875rem;color:#6B7280;">
                        <li class="mb-2">✅ Short, memorable names work best</li>
                        <li class="mb-2">✅ Avoid hyphens and numbers</li>
                        <li class="mb-2">✅ <strong>.ug</strong> and <strong>.co.ug</strong> for Uganda businesses</li>
                        <li class="mb-2">✅ <strong>.com</strong> is most recognised globally</li>
                        <li>✅ Register for multiple years to save</li>
                    </ul>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary w-100 mt-2" style="border-radius:8px;font-size:.875rem;">
                        Need help choosing? Contact us
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Empty state --}}
    <div id="emptySection" style="text-align:center;padding:60px 0;">
        <i class="bi bi-search" style="font-size:3rem;color:#d1d5db;display:block;margin-bottom:16px;"></i>
        <h5 class="text-muted">Enter a domain name above to check availability</h5>
        <p class="text-muted small">We check .com, .net, .org, .ug, .co.ug and more</p>
    </div>

</div>

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';

async function searchDomain() {
    const input = document.getElementById('domainInput').value.trim();
    if (!input) return;

    // Clean input
    const sld = input.replace(/\.(com|net|org|biz|info|ug|co\.ug|ac\.ug|or\.ug)$/i, '')
                      .replace(/[^a-z0-9\-]/gi, '').toLowerCase();

    if (sld.length < 2) {
        alert('Please enter a valid domain name (at least 2 characters).');
        return;
    }

    // Update UI
    document.getElementById('emptySection').style.display   = 'none';
    document.getElementById('resultsSection').style.display = 'none';
    document.getElementById('loadingSection').style.display = 'block';
    document.getElementById('searchBtn').disabled = true;
    document.getElementById('resultsTitle').textContent = 'Checking availability for "' + sld + '"...';

    try {
        const resp = await fetch('{{ route("domains.check") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ domain: sld })
        });

        if (!resp.ok) {
            throw new Error('Server error: ' + resp.status);
        }

        const results = await resp.json();
        renderResults(sld, results);

    } catch (err) {
        document.getElementById('loadingSection').style.display = 'none';
        document.getElementById('emptySection').style.display = 'block';
        document.getElementById('emptySection').innerHTML =
            '<i class="bi bi-exclamation-circle" style="font-size:3rem;color:#dc3545;display:block;margin-bottom:12px;"></i>' +
            '<h5 class="text-danger">Search failed</h5><p class="text-muted">' + err.message + '</p>';
    } finally {
        document.getElementById('searchBtn').disabled = false;
    }
}

function renderResults(sld, results) {
    document.getElementById('loadingSection').style.display = 'none';
    document.getElementById('resultsSection').style.display = 'block';
    document.getElementById('resultsTitle').textContent = 'Results for "' + sld + '" — ' + results.length + ' extensions checked';

    const available = results.filter(r => r.available);
    const taken     = results.filter(r => !r.available);

    // Available
    const availList = document.getElementById('availableList');
    const availHdr  = document.getElementById('availableHeader');
    if (available.length > 0) {
        availHdr.style.display = 'block';
        availHdr.textContent = '✅ Available (' + available.length + ')';
        availList.innerHTML = available.map(r => `
            <div class="result-card available">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div>
                        <div class="domain-name">${r.domain}</div>
                        <div style="font-size:.8rem;color:#6B7280;">Available for registration</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end">
                        <div class="domain-price">$${r.price}/yr</div>
                        <span class="badge-available">Available</span>
                    </div>
                    <form method="POST" action="{{ route('cart.add.public') }}">
                        <input type="hidden" name="_token"        value="{{ csrf_token() }}">
                        <input type="hidden" name="type"          value="domain">
                        <input type="hidden" name="name"          value="Domain: ${r.domain}">
                        <input type="hidden" name="billing_cycle" value="yearly">
                        <input type="hidden" name="price"         value="${parseFloat(r.price)}">
                        <input type="hidden" name="domain"        value="${r.domain}">
                        <button type="submit" class="btn-register">
                            Add to Cart →
                        </button>
                    </form>
                </div>
            </div>
        `).join('');
    } else {
        availHdr.style.display = 'none';
        availList.innerHTML = '<div class="alert alert-warning">No available domains found for "' + sld + '". Try a different name.</div>';
        availHdr.style.display = 'block';
        availHdr.innerHTML = '❌ No Available Domains';
    }

    // Taken
    const takenList = document.getElementById('takenList');
    const takenHdr  = document.getElementById('takenHeader');
    if (taken.length > 0) {
        takenHdr.style.display = 'block';
        takenHdr.textContent = 'Unavailable (' + taken.length + ')';
        takenList.innerHTML = taken.map(r => `
            <div class="result-card taken">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-x-circle-fill text-muted fs-5"></i>
                    <div class="domain-name" style="color:#9ca3af;">${r.domain}</div>
                </div>
                <span class="badge-taken">Taken</span>
            </div>
        `).join('');
    }
}

// Auto-search if domain provided in URL
@if($query)
window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('domainInput').value = '{{ addslashes($query) }}';
    searchDomain();
});
@endif

// Search on Enter key
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('domainInput').addEventListener('keydown', e => {
        if (e.key === 'Enter') searchDomain();
    });
});
</script>
@endpush
@endsection
