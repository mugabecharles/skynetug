@extends('layouts.dashboard')
@section('page_title', $domain->domain_name)

@section('content')
<div class="mb-4">
    <a href="{{ route('dashboard.domains.index') }}" class="btn btn-sm btn-outline-secondary me-2" style="border-radius:8px;">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row g-4">
    {{-- Domain Info --}}
    <div class="col-lg-4">
        <div class="bg-white rounded-3 border p-4 mb-3">
            <h6 class="fw-bold mb-3">Domain Details</h6>
            <dl class="row mb-0" style="font-size:0.875rem;">
                <dt class="col-5 text-muted">Domain</dt>
                <dd class="col-7 fw-semibold">{{ $domain->domain_name }}</dd>
                <dt class="col-5 text-muted">Status</dt>
                <dd class="col-7">
                    @php $dc = match($domain->status) { 'active'=>'success','expired'=>'danger','pending'=>'warning', default=>'secondary' }; @endphp
                    <span class="badge bg-{{ $dc }}-subtle text-{{ $dc }} rounded-pill">{{ ucfirst($domain->status) }}</span>
                </dd>
                <dt class="col-5 text-muted">Registered</dt>
                <dd class="col-7">{{ $domain->registration_date ? \Carbon\Carbon::parse($domain->registration_date)->format('d M Y') : '—' }}</dd>
                <dt class="col-5 text-muted">Expires</dt>
                <dd class="col-7">{{ $domain->expiry_date ? \Carbon\Carbon::parse($domain->expiry_date)->format('d M Y') : '—' }}</dd>
                <dt class="col-5 text-muted">Registrar</dt>
                <dd class="col-7">{{ ucfirst($domain->registrar ?? 'SkyNetug') }}</dd>
            </dl>
        </div>

        {{-- Domain Lock --}}
        <div class="bg-white rounded-3 border p-4 mb-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h6 class="fw-bold mb-0">Domain Lock</h6>
                    <p class="text-muted small mb-0">Prevents unauthorized transfers</p>
                </div>
                <span class="badge {{ $domain->is_locked ? 'bg-success' : 'bg-secondary' }}">{{ $domain->is_locked ? 'Locked' : 'Unlocked' }}</span>
            </div>
            <form method="POST" action="{{ route('dashboard.domains.lock.toggle', $domain->id) }}">
                @csrf
                <button class="btn btn-sm {{ $domain->is_locked ? 'btn-outline-danger' : 'btn-outline-success' }} w-100">
                    {{ $domain->is_locked ? 'Unlock Domain' : 'Lock Domain' }}
                </button>
            </form>
        </div>

        {{-- WHOIS Privacy --}}
        <div class="bg-white rounded-3 border p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h6 class="fw-bold mb-0">WHOIS Privacy</h6>
                    <p class="text-muted small mb-0">Hides your personal contact info</p>
                </div>
                <span class="badge {{ $domain->whois_privacy ? 'bg-success' : 'bg-secondary' }}">{{ $domain->whois_privacy ? 'Enabled' : 'Disabled' }}</span>
            </div>
            <form method="POST" action="{{ route('dashboard.domains.privacy.toggle', $domain->id) }}">
                @csrf
                <button class="btn btn-sm {{ $domain->whois_privacy ? 'btn-outline-warning' : 'btn-outline-primary' }} w-100">
                    {{ $domain->whois_privacy ? 'Disable Privacy' : 'Enable Privacy' }}
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        {{-- Nameservers --}}
        <div class="bg-white rounded-3 border p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-diagram-3 me-2 text-sky"></i>Nameservers</h6>
            <form method="POST" action="{{ route('dashboard.domains.nameservers.update', $domain->id) }}">
                @csrf
                <div class="row g-2">
                    @foreach(['ns1' => $domain->nameserver_1, 'ns2' => $domain->nameserver_2, 'ns3' => $domain->nameserver_3, 'ns4' => $domain->nameserver_4] as $field => $value)
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ strtoupper($field) }} {{ in_array($field, ['ns1','ns2']) ? '<span class="text-danger">*</span>' : '(optional)' }}</label>
                        <input type="text" name="{{ $field }}" class="form-control form-control-sm"
                            value="{{ $value }}" placeholder="ns1.skynetug.com" {{ in_array($field, ['ns1','ns2']) ? 'required' : '' }}>
                    </div>
                    @endforeach
                </div>
                <button class="btn btn-sky btn-sm mt-3"><i class="bi bi-save me-1"></i>Update Nameservers</button>
            </form>
        </div>

        {{-- DNS Records --}}
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-hdd-network me-2 text-sky"></i>DNS Records</h6>
            <div class="table-responsive mb-3">
                <table class="table table-sm align-middle" style="font-size:0.82rem;">
                    <thead class="table-light">
                        <tr><th>Type</th><th>Name</th><th>Value</th><th>TTL</th></tr>
                    </thead>
                    <tbody id="dns-records">
                        @forelse($domain->dnsRecords as $record)
                        <tr>
                            <td><span class="badge bg-light text-dark border">{{ $record->type }}</span></td>
                            <td class="font-monospace">{{ $record->name }}</td>
                            <td class="font-monospace text-truncate" style="max-width:200px;">{{ $record->value }}</td>
                            <td>{{ $record->ttl }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No DNS records configured.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p class="text-muted small">
                <i class="bi bi-info-circle me-1"></i>To modify DNS records, please open a support ticket or contact our technical team.
            </p>
        </div>
    </div>
</div>
@endsection
