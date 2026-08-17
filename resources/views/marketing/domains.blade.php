@extends('layouts.app')
@section('title', 'Domain Registration Uganda - .com .ug .co.ug')

@push('styles')
<style>
.domain-hero { background:linear-gradient(135deg,#0A0F1E,#0A0F1E); padding:4rem 0 3rem; }
.tld-card { border:1.5px solid #e8ecf0; border-radius:14px; padding:1.25rem; text-align:center; transition:all .2s; background:#fff; cursor:pointer; }
.tld-card:hover { border-color:#0066FF; box-shadow:0 8px 24px rgba(0,102,255,.12); transform:translateY(-2px); }
.result-item { background:#fff; border-radius:12px; border:1.5px solid #e8ecf0; padding:1rem 1.25rem; margin-bottom:.5rem; display:flex; align-items:center; justify-content:space-between; }
.result-item.available { border-color:#00C896; }
.result-item.unavailable { opacity:.6; }
</style>
@endpush

@section('content')
<div class="domain-hero">
    <div class="container text-center">
        <span class="section-badge" style="background:rgba(0,200,150,.15);color:#00C896;border:1px solid rgba(0,200,150,.3);">Domain Registration</span>
        <h1 class="fw-bold text-white mb-3" style="font-size:clamp(1.8rem,4vw,3rem);">Find Your Perfect Domain Name</h1>
        <p class="text-white-50 mb-4">Register .com, .ug, .co.ug and more. Instant registration, free DNS management.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap mb-4">
            <a href="{{ route('domains.transfer') }}" class="btn btn-outline-light btn-sm px-4" style="border-radius:20px;">
                <i class="bi bi-arrow-left-right me-2"></i>Transfer an Existing Domain
            </a>
        </div>

        <div class="mx-auto" style="max-width:620px;">
            <div class="input-group input-group-lg">
                <input type="text" id="domainSearch" class="form-control"
                    placeholder="yourname.com or yourname" style="border-radius:12px 0 0 12px;background:rgba(255,255,255,.1);color:#fff;border-color:rgba(255,255,255,.2);">
                <button class="btn btn-sky px-5" onclick="searchDomain()" style="border-radius:0 12px 12px 0;">
                    <i class="bi bi-search me-2"></i>Search
                </button>
            </div>
            <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                @foreach(['.com','.ug','.co.ug','.net','.org','.biz'] as $tld)
                <span class="badge px-3 py-2" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.8);border-radius:20px;font-size:.8rem;cursor:pointer;"
                    onclick="document.getElementById('domainSearch').value += '{{ $tld }}'">{{ $tld }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    {{-- Search Results --}}
    <div id="searchResults" style="display:none;" class="mb-5">
        <h5 class="fw-bold mb-3" id="resultsTitle"></h5>
        <div id="resultsList"></div>
    </div>

    {{-- Loading --}}
    <div id="searchLoading" style="display:none;" class="text-center py-4">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="text-muted mt-2">Checking availability...</p>
    </div>

    {{-- TLD Pricing Table --}}
    <div class="text-center mb-4">
        <span class="section-badge">Popular TLDs</span>
        <h2 class="fw-bold">Domain Pricing</h2>
    </div>
    <div class="row g-3 mb-5">
        @foreach($tlds as $tld)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="tld-card" onclick="document.getElementById('domainSearch').value=''; document.getElementById('domainSearch').focus();">
                <div class="fw-bold text-sky fs-4">{{ $tld['tld'] }}</div>
                <div class="text-muted small mt-1">Registration</div>
                <div class="fw-bold mt-1">$ {{ number_format($tld['price']) }}</div>
                <div class="text-muted" style="font-size:.75rem;">/year</div>
                @if($tld['popular'] ?? false)
                    <span class="badge bg-success-subtle text-success mt-2" style="font-size:.7rem;">Popular</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Why Register With Us --}}
    <div class="text-center mb-4">
        <h3 class="fw-bold">Why Register Domains with SkyNetug?</h3>
    </div>
    <div class="row g-4">
        @foreach([
            ['icon'=>'bi-shield-lock','title'=>'Free WHOIS Privacy','desc'=>'Hide your personal information from the public WHOIS database.'],
            ['icon'=>'bi-hdd-network','title'=>'Free DNS Management','desc'=>'Full DNS control — A, CNAME, MX, TXT, NS records and more.'],
            ['icon'=>'bi-arrow-repeat','title'=>'Auto-Renewal','desc'=>'Never lose your domain. Automatic renewal with reminders 30 days before expiry.'],
            ['icon'=>'bi-lock','title'=>'Domain Lock','desc'=>'Protect your domain from unauthorized transfers with registrar lock.'],
        ] as $f)
        <div class="col-md-6 col-lg-3 text-center">
            <div class="p-4 bg-light rounded-3">
                <i class="bi {{ $f['icon'] }} fs-2 text-sky d-block mb-2"></i>
                <h6 class="fw-bold">{{ $f['title'] }}</h6>
                <p class="text-muted small mb-0">{{ $f['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
async function searchDomain() {
    const query = document.getElementById('domainSearch').value.trim();
    if (!query) return;

    // Strip TLD if present for cleaner search
    const sld = query.replace(/\.(com|net|org|biz|info|ug|co\.ug|ac\.ug|or\.ug)$/, '').toLowerCase().replace(/[^a-z0-9-]/g,'');
    if (!sld) return;

    document.getElementById('searchResults').style.display = 'none';
    document.getElementById('searchLoading').style.display = 'block';

    try {
        const resp = await fetch('{{ route("domains.check") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ domain: sld })
        });
        const results = await resp.json();
        renderResults(sld, results);
    } catch (e) {
        document.getElementById('searchLoading').style.display = 'none';
        alert('Search failed. Please try again.');
    }
}

const CSRF = '{{ csrf_token() }}';
const CART_ADD_URL = '{{ route("cart.add.public") }}';

function renderResults(sld, results) {
    document.getElementById('searchLoading').style.display = 'none';
    document.getElementById('searchResults').style.display = 'block';
    document.getElementById('resultsTitle').textContent = 'Search results for "' + sld + '"';

    const list = document.getElementById('resultsList');
    list.innerHTML = results.map(r => `
        <div class="result-item ${r.available ? 'available' : 'unavailable'}">
            <div class="d-flex align-items-center gap-3">
                <i class="bi ${r.available ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-muted'} fs-5"></i>
                <div>
                    <span class="fw-semibold">${r.domain}</span>
                    ${r.available ? '<span class="badge bg-success-subtle text-success ms-2 small">Available</span>' : '<span class="badge bg-secondary-subtle text-secondary ms-2 small">Taken</span>'}
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="fw-bold">$ ${r.price.toLocaleString()}/yr</span>
                ${r.available
                    ? `<form method="POST" action="${CART_ADD_URL}" class="d-inline">
                           <input type="hidden" name="_token"        value="${CSRF}">
                           <input type="hidden" name="type"          value="domain">
                           <input type="hidden" name="name"          value="Domain: ${r.domain}">
                           <input type="hidden" name="billing_cycle" value="yearly">
                           <input type="hidden" name="price"         value="${parseFloat(r.price)}">
                           <input type="hidden" name="domain"        value="${r.domain}">
                           <button type="submit" class="btn btn-sky btn-sm">
                               <i class="bi bi-cart-plus me-1"></i>Add to Cart
                           </button>
                       </form>`
                    : `<button class="btn btn-sm btn-outline-secondary" disabled>Unavailable</button>`
                }
            </div>
        </div>
    `).join('');
}

// Search on Enter key
document.getElementById('domainSearch').addEventListener('keydown', e => {
    if (e.key === 'Enter') searchDomain();
});
</script>
@endpush
@endsection


