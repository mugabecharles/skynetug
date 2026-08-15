@extends('layouts.dashboard')
@section('page_title', 'My Domains')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">My Domains</h5>
        <p class="text-muted small mb-0">Manage your registered domain names</p>
    </div>
    <a href="{{ route('domains') }}" class="btn btn-sky btn-sm">
        <i class="bi bi-plus me-1"></i> Register New Domain
    </a>
</div>

<div class="bg-white rounded-3 border">
    @if($domains->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-link-45deg display-5 d-block mb-3 opacity-30"></i>
            <h6>No domains registered yet</h6>
            <p class="small">Register your first domain name to get started.</p>
            <a href="{{ route('domains') }}" class="btn btn-sky btn-sm">Register a Domain</a>
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:0.875rem;">
            <thead class="table-light">
                <tr>
                    <th>Domain Name</th>
                    <th>TLD</th>
                    <th>Status</th>
                    <th>Auto Renew</th>
                    <th>Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($domains as $domain)
                <tr>
                    <td class="fw-semibold">{{ $domain->domain_name }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $domain->tld }}</span></td>
                    <td>
                        @php $dc = match($domain->status) { 'active'=>'success','expired'=>'danger','pending'=>'warning', default=>'secondary' }; @endphp
                        <span class="badge bg-{{ $dc }}-subtle text-{{ $dc }} rounded-pill">{{ ucfirst($domain->status) }}</span>
                    </td>
                    <td>
                        @if($domain->auto_renew)
                            <i class="bi bi-check-circle-fill text-success"></i>
                        @else
                            <i class="bi bi-x-circle-fill text-muted"></i>
                        @endif
                    </td>
                    <td>
                        @if($domain->expiry_date)
                            @php $expiry = \Carbon\Carbon::parse($domain->expiry_date); @endphp
                            <span class="{{ $expiry->diffInDays() < 30 ? 'text-danger fw-semibold' : '' }}">
                                {{ $expiry->format('d M Y') }}
                            </span>
                            @if($expiry->diffInDays() < 30)
                                <span class="badge bg-danger-subtle text-danger ms-1" style="font-size:0.7rem;">Expires soon</span>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('dashboard.domains.show', $domain->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;font-size:0.75rem;">Manage</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $domains->links() }}</div>
    @endif
</div>
@endsection
