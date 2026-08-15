@extends('layouts.dashboard')
@section('page_title', $domain->domain_name)

@section('content')
<div class="mb-4"><a href="{{ route('admin.domains.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a></div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Domain Details</h6>
            <dl class="row small mb-0">
                <dt class="col-5 text-muted">Domain</dt><dd class="col-7 fw-semibold">{{ $domain->domain_name }}</dd>
                <dt class="col-5 text-muted">TLD</dt><dd class="col-7">{{ $domain->tld }}</dd>
                <dt class="col-5 text-muted">Customer</dt><dd class="col-7"><a href="{{ route('admin.users.show',$domain->user_id) }}">{{ $domain->user->name }}</a></dd>
                @php $c=match($domain->status){'active'=>'success','expired'=>'danger','pending'=>'warning',default=>'secondary'}; @endphp
                <dt class="col-5 text-muted">Status</dt><dd class="col-7"><span class="badge bg-{{ $c }}-subtle text-{{ $c }}">{{ ucfirst($domain->status) }}</span></dd>
                <dt class="col-5 text-muted">Registrar</dt><dd class="col-7">{{ $domain->registrar ?? '—' }}</dd>
                <dt class="col-5 text-muted">Registered</dt><dd class="col-7">{{ $domain->registration_date ? \Carbon\Carbon::parse($domain->registration_date)->format('d M Y') : '—' }}</dd>
                <dt class="col-5 text-muted">Expires</dt><dd class="col-7 {{ $domain->expiry_date && \Carbon\Carbon::parse($domain->expiry_date)->diffInDays()<30?'text-danger fw-semibold':'' }}">{{ $domain->expiry_date ? \Carbon\Carbon::parse($domain->expiry_date)->format('d M Y') : '—' }}</dd>
                <dt class="col-5 text-muted">Auto Renew</dt><dd class="col-7">{{ $domain->auto_renew?'Yes':'No' }}</dd>
                <dt class="col-5 text-muted">Domain Lock</dt><dd class="col-7">{{ $domain->is_locked?'Locked':'Unlocked' }}</dd>
                <dt class="col-5 text-muted">WHOIS Privacy</dt><dd class="col-7">{{ $domain->whois_privacy?'Enabled':'Disabled' }}</dd>
                <dt class="col-5 text-muted">NS1</dt><dd class="col-7 font-monospace small">{{ $domain->nameserver_1 ?? '—' }}</dd>
                <dt class="col-5 text-muted">NS2</dt><dd class="col-7 font-monospace small">{{ $domain->nameserver_2 ?? '—' }}</dd>
            </dl>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">DNS Records</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0" style="font-size:.85rem;">
                    <thead class="table-light"><tr><th>Type</th><th>Name</th><th>Value</th><th>TTL</th><th>Priority</th></tr></thead>
                    <tbody>
                        @forelse($domain->dnsRecords as $rec)
                        <tr>
                            <td><span class="badge bg-light text-dark border">{{ $rec->type }}</span></td>
                            <td class="font-monospace">{{ $rec->name }}</td>
                            <td class="font-monospace small">{{ Str::limit($rec->value,50) }}</td>
                            <td>{{ $rec->ttl }}</td>
                            <td>{{ $rec->priority ?: '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">No DNS records.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
