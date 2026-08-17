@extends('layouts.app')
@section('title', 'Shared Web Hosting Uganda')
@section('meta_description', 'Fast shared web hosting plans for Uganda businesses. Starting from $ 15,000/month. Free SSL, cPanel, 99.9% uptime.')

@push('styles')
<style>
.hosting-hero { background: linear-gradient(135deg,#0A0F1E,#0A0F1E); padding: 4rem 0 3rem; }
.plan-card { border:1.5px solid #e8ecf0; border-radius:20px; padding:2rem; transition:all .25s; background:#fff; position:relative; }
.plan-card:hover { border-color:#0066FF; box-shadow:0 16px 40px rgba(0,102,255,.12); transform:translateY(-4px); }
.plan-card.popular { border-color:#0066FF; box-shadow:0 0 0 4px rgba(0,102,255,.08); }
.popular-badge { position:absolute; top:-13px; left:50%; transform:translateX(-50%); background:#0066FF; color:#fff; font-size:.72rem; font-weight:700; border-radius:20px; padding:.25rem 1rem; white-space:nowrap; }
.billing-toggle .btn-check:checked + .btn { background:#0066FF; color:#fff; border-color:#0066FF; }
</style>
@endpush

@section('content')
<div class="hosting-hero">
    <div class="container text-center">
        <span class="section-badge" style="background:rgba(0,200,150,.15);color:#00C896;border:1px solid rgba(0,200,150,.3);">Shared Hosting</span>
        <h1 class="fw-bold text-white mb-3" style="font-size:clamp(1.8rem,4vw,3rem);">Fast & Affordable Web Hosting<br>for Uganda</h1>
        <p class="text-white-50 mb-4" style="max-width:520px;margin:0 auto;">All plans include free SSL, cPanel, 1-click WordPress, and 24/7 support. Pay with MTN or Airtel Money.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap text-white-50" style="font-size:.85rem;">
            @foreach(['✅ Free SSL','✅ Free cPanel','✅ 99.9% Uptime','✅ Daily Backups','✅ Mobile Money'] as $f)
            <span>{{ $f }}</span>
            @endforeach
        </div>
    </div>
</div>

<div class="container py-5">
    {{-- Billing Toggle --}}
    <div class="text-center mb-5">
        <div class="btn-group billing-toggle" role="group">
            <input type="radio" class="btn-check" name="billing" id="b-monthly" value="monthly">
            <label class="btn btn-outline-primary px-4" for="b-monthly">Monthly</label>
            <input type="radio" class="btn-check" name="billing" id="b-yearly" value="yearly" checked>
            <label class="btn btn-outline-primary px-4" for="b-yearly">Yearly <span class="badge bg-success ms-1">Save 20%</span></label>
        </div>
    </div>

    @if($packages->isEmpty())
    {{-- Fallback static plans --}}
    <div class="row g-4 justify-content-center">
        @foreach([
            ['name'=>'Starter',      'price_yearly'=>180000, 'price_monthly'=>18000,  'price_biennially'=>252000, 'disk'=>'5 GB SSD',       'bw'=>'Unlimited','emails'=>'5',         'dbs'=>'1',         'domains'=>'1',         'ssl'=>true,'softaculous'=>true,'popular'=>false,'slug'=>'starter'],
            ['name'=>'Business',     'price_yearly'=>360000, 'price_monthly'=>35000,  'price_biennially'=>504000, 'disk'=>'20 GB SSD',      'bw'=>'Unlimited','emails'=>'20',        'dbs'=>'5',         'domains'=>'5',         'ssl'=>true,'softaculous'=>true,'popular'=>true, 'slug'=>'business'],
            ['name'=>'Professional', 'price_yearly'=>660000, 'price_monthly'=>65000,  'price_biennially'=>924000, 'disk'=>'50 GB SSD',      'bw'=>'Unlimited','emails'=>'Unlimited', 'dbs'=>'Unlimited', 'domains'=>'Unlimited', 'ssl'=>true,'softaculous'=>true,'popular'=>false,'slug'=>'professional'],
            ['name'=>'Unlimited',    'price_yearly'=>960000, 'price_monthly'=>90000,  'price_biennially'=>1344000,'disk'=>'Unlimited SSD',  'bw'=>'Unlimited','emails'=>'Unlimited', 'dbs'=>'Unlimited', 'domains'=>'Unlimited', 'ssl'=>true,'softaculous'=>true,'popular'=>false,'slug'=>'unlimited'],
        ] as $p)
        <div class="col-md-6 col-lg-3">
            <div class="plan-card {{ $p['popular'] ? 'popular' : '' }}">
                @if($p['popular'])<div class="popular-badge">⭐ Most Popular</div>@endif
                <h5 class="fw-bold mb-1">{{ $p['name'] }}</h5>
                <p class="text-muted small mb-3">{{ $p['popular'] ? 'Growing businesses' : 'Ideal for '.strtolower($p['name']) }}</p>
                <div class="mb-4">
                    <div class="price-yearly">
                        <span style="font-size:2rem;font-weight:800;color:#0A0F1E;">$ {{ number_format($p['price_yearly']/12, 2) }}</span>
                        <span class="text-muted small">/mo</span>
                        <div class="text-muted small">Billed $ {{ number_format($p['price_yearly'], 2) }}/yr</div>
                    </div>
                    <div class="price-monthly" style="display:none;">
                        <span style="font-size:2rem;font-weight:800;color:#0A0F1E;">$ {{ number_format($p['price_monthly'], 2) }}</span>
                        <span class="text-muted small">/mo</span>
                    </div>
                </div>
                <ul class="list-unstyled mb-4" style="font-size:.875rem;">
                    @foreach([
                        'bi-hdd'          => $p['disk'].' Storage',
                        'bi-arrow-down-up'=> $p['bw'].' Bandwidth',
                        'bi-envelope'     => $p['emails'].' Email Accounts',
                        'bi-database'     => $p['dbs'].' Database(s)',
                        'bi-globe'        => $p['domains'].' Domain(s)',
                        'bi-shield-check' => 'Free SSL Certificate',
                        'bi-lightning'    => '1-Click WordPress',
                        'bi-headset'      => '24/7 Support',
                    ] as $icon => $feat)
                    <li class="d-flex align-items-center gap-2 py-1">
                        <i class="bi {{ $icon }}" style="color:#00C896;width:16px;"></i>
                        <span>{{ $feat }}</span>
                    </li>
                    @endforeach
                </ul>
                {{-- Add to Cart form — cycle + price updated by JS toggle --}}
                <form method="POST" action="{{ route('cart.add.public') }}" class="cart-add-form">
                    @csrf
                    <input type="hidden" name="type"          value="hosting">
                    <input type="hidden" name="name"          value="{{ $p['name'] }} Hosting">
                    <input type="hidden" name="billing_cycle" class="billing-cycle-input" value="yearly">
                    <input type="hidden" name="price"         class="price-input"
                           data-monthly="{{ $p['price_monthly'] }}"
                           data-yearly="{{ $p['price_yearly'] }}"
                           data-biennially="{{ $p['price_biennially'] }}"
                           value="{{ $p['price_yearly'] }}">
                    <button type="submit" class="btn {{ $p['popular'] ? 'btn-sky' : 'btn-outline-primary' }} w-100 py-2 fw-semibold">
                        <i class="bi bi-cart-plus me-2"></i>Add to Cart &amp; Continue
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="row g-4 justify-content-center">
        @foreach($packages as $pkg)
        <div class="col-md-6 col-lg-3">
            <div class="plan-card {{ $pkg->is_featured ? 'popular' : '' }}">
                @if($pkg->is_featured)<div class="popular-badge">⭐ Most Popular</div>@endif
                <h5 class="fw-bold mb-1">{{ $pkg->name }}</h5>
                <p class="text-muted small mb-3">{{ $pkg->description }}</p>
                <div class="mb-4">
                    <div class="price-yearly">
                        <span style="font-size:2rem;font-weight:800;">$ {{ number_format($pkg->price_yearly/12, 2) }}</span>
                        <span class="text-muted small">/mo</span>
                        <div class="text-muted small">Billed $ {{ number_format($pkg->price_yearly, 2) }}/yr</div>
                    </div>
                    <div class="price-monthly" style="display:none;">
                        <span style="font-size:2rem;font-weight:800;">$ {{ number_format($pkg->price_monthly, 2) }}</span>
                        <span class="text-muted small">/mo</span>
                    </div>
                </div>
                <ul class="list-unstyled mb-4" style="font-size:.875rem;">
                    <li class="d-flex align-items-center gap-2 py-1"><i class="bi bi-hdd" style="color:#00C896;width:16px;"></i>{{ $pkg->disk_space_mb==0?'Unlimited':number_format($pkg->disk_space_mb/1024,0).' GB' }} Storage</li>
                    <li class="d-flex align-items-center gap-2 py-1"><i class="bi bi-arrow-down-up" style="color:#00C896;width:16px;"></i>{{ $pkg->bandwidth_mb==0?'Unlimited':number_format($pkg->bandwidth_mb/1024,0).' GB' }} Bandwidth</li>
                    <li class="d-flex align-items-center gap-2 py-1"><i class="bi bi-envelope" style="color:#00C896;width:16px;"></i>{{ $pkg->email_accounts==0?'Unlimited':$pkg->email_accounts }} Email Accounts</li>
                    <li class="d-flex align-items-center gap-2 py-1"><i class="bi bi-shield-check" style="color:#00C896;width:16px;"></i>Free SSL Certificate</li>
                    @if($pkg->softaculous_included)<li class="d-flex align-items-center gap-2 py-1"><i class="bi bi-lightning" style="color:#00C896;width:16px;"></i>1-Click WordPress</li>@endif
                    @if($pkg->backup_included)<li class="d-flex align-items-center gap-2 py-1"><i class="bi bi-cloud-check" style="color:#00C896;width:16px;"></i>Free Daily Backups</li>@endif
                    @if($pkg->features) @foreach((array)$pkg->features as $feat)<li class="d-flex align-items-center gap-2 py-1"><i class="bi bi-check2" style="color:#00C896;width:16px;"></i>{{ $feat }}</li>@endforeach @endif
                    <li class="d-flex align-items-center gap-2 py-1"><i class="bi bi-headset" style="color:#00C896;width:16px;"></i>24/7 Support</li>
                </ul>
                <form method="POST" action="{{ route('cart.add.public') }}" class="cart-add-form">
                    @csrf
                    <input type="hidden" name="type"          value="hosting">
                    <input type="hidden" name="package_id"    value="{{ $pkg->id }}">
                    <input type="hidden" name="name"          value="{{ $pkg->name }} Hosting">
                    <input type="hidden" name="billing_cycle" class="billing-cycle-input" value="yearly">
                    <input type="hidden" name="price"         class="price-input"
                           data-monthly="{{ $pkg->price_monthly }}"
                           data-yearly="{{ $pkg->price_yearly }}"
                           data-biennially="{{ $pkg->price_biennially }}"
                           value="{{ $pkg->price_yearly }}">
                    <button type="submit" class="btn {{ $pkg->is_featured ? 'btn-sky' : 'btn-outline-primary' }} w-100 py-2 fw-semibold">
                        <i class="bi bi-cart-plus me-2"></i>Add to Cart &amp; Continue
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@push('scripts')
<script>
// Billing cycle toggle — update all hidden inputs
document.querySelectorAll('input[name="billing"]').forEach(r => {
    r.addEventListener('change', () => {
        const cycle = r.value; // monthly | yearly
        // Show/hide prices
        document.querySelectorAll('.price-yearly').forEach(el => el.style.display = cycle === 'monthly' ? 'none' : 'block');
        document.querySelectorAll('.price-monthly').forEach(el => el.style.display = cycle === 'monthly' ? 'block' : 'none');
        // Update hidden form fields
        document.querySelectorAll('.cart-add-form').forEach(form => {
            form.querySelector('.billing-cycle-input').value = cycle;
            const priceInput = form.querySelector('.price-input');
            priceInput.value = priceInput.dataset[cycle] || priceInput.dataset.yearly;
        });
    });
});
</script>
@endpush
@endsection
