@extends('layouts.dashboard')
@section('page_title', 'Domains')

@section('content')
<div class="bg-white rounded-3 border p-3 mb-4">
    <form method="GET" class="row g-2">
        <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Domain name…" value="{{ request('search') }}"></div>
        <div class="col-md-2">
            <select name="tld" class="form-select form-select-sm">
                <option value="">All TLDs</option>
                @foreach(['.com','.net','.org','.ug','.co.ug','.biz','.info','.ac.ug','.or.ug'] as $t)
                <option value="{{ $t }}" {{ request('tld')==$t?'selected':'' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                @foreach(['active','pending','expired','transferred','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-sky btn-sm w-100">Filter</button></div>
    </form>
</div>

<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light">
                <tr><th>Domain</th><th>TLD</th><th>Customer</th><th>Registrar</th><th>Status</th><th>Expiry</th><th>Auto Renew</th></tr>
            </thead>
            <tbody>
                @forelse($domains as $domain)
                <tr>
                    <td class="fw-semibold">{{ $domain->domain_name }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $domain->tld }}</span></td>
                    <td>{{ $domain->user->name }}</td>
                    <td class="small text-muted">{{ $domain->registrar ?? '—' }}</td>
                    <td>
                        @php $c=match($domain->status){'active'=>'success','expired'=>'danger','pending'=>'warning','transferred'=>'info',default=>'secondary'}; @endphp
                        <span class="badge bg-{{ $c }}-subtle text-{{ $c }} rounded-pill">{{ ucfirst($domain->status) }}</span>
                    </td>
                    <td class="small {{ $domain->expiry_date && \Carbon\Carbon::parse($domain->expiry_date)->diffInDays()<30 ? 'text-danger fw-semibold' : 'text-muted' }}">
                        {{ $domain->expiry_date ? \Carbon\Carbon::parse($domain->expiry_date)->format('d M Y') : '—' }}
                    </td>
                    <td>{{ $domain->auto_renew ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle-fill text-muted"></i>' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No domains found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $domains->links() }}</div>
</div>
@endsection
