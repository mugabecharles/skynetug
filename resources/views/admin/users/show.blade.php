@extends('layouts.dashboard')
@section('page_title', 'User: ' . $user->name)

@section('content')
<div class="mb-4 d-flex gap-2">
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a>
    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-sky" style="border-radius:8px;"><i class="bi bi-pencil me-1"></i>Edit</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="bg-white rounded-3 border p-4 text-center mb-4">
            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                 style="width:72px;height:72px;background:#0066FF;color:#fff;font-size:1.6rem;font-weight:700;">
                {{ strtoupper(substr($user->name,0,2)) }}
            </div>
            <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
            <p class="text-muted small mb-2">{{ $user->email }}</p>
            @php $rc=match($user->role){'super_admin'=>'danger','billing_manager'=>'warning','technical_admin'=>'info','support_agent'=>'primary','sales_manager'=>'success',default=>'secondary'}; @endphp
            <span class="badge bg-{{ $rc }}-subtle text-{{ $rc }}">{{ ucfirst(str_replace('_',' ',$user->role)) }}</span>
            <div class="mt-2">
                @if($user->is_active)<span class="badge bg-success-subtle text-success">Active</span>
                @else<span class="badge bg-danger-subtle text-danger">Disabled</span>@endif
            </div>
        </div>
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Account Details</h6>
            <dl class="row small mb-0">
                <dt class="col-5 text-muted">Phone</dt><dd class="col-7">{{ $user->phone ?? '—' }}</dd>
                <dt class="col-5 text-muted">Country</dt><dd class="col-7">{{ $user->country ?? '—' }}</dd>
                <dt class="col-5 text-muted">City</dt><dd class="col-7">{{ $user->city ?? '—' }}</dd>
                <dt class="col-5 text-muted">Joined</dt><dd class="col-7">{{ $user->created_at->format('d M Y') }}</dd>
                <dt class="col-5 text-muted">2FA</dt><dd class="col-7">{{ $user->two_factor_enabled ? '✅ Enabled' : '❌ Disabled' }}</dd>
                <dt class="col-5 text-muted">Referral</dt><dd class="col-7 font-monospace">{{ $user->referral_code ?? '—' }}</dd>
            </dl>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="row g-3 mb-4">
            @foreach([
                ['label'=>'Hosting Accounts','value'=>$user->hostingAccounts()->count(),'icon'=>'bi-server','color'=>'#0066FF'],
                ['label'=>'Domains','value'=>$user->domains()->count(),'icon'=>'bi-link-45deg','color'=>'#00C896'],
                ['label'=>'Invoices','value'=>$user->invoices()->count(),'icon'=>'bi-receipt','color'=>'#f59e0b'],
                ['label'=>'Support Tickets','value'=>$user->supportTickets()->count(),'icon'=>'bi-headset','color'=>'#ef4444'],
            ] as $s)
            <div class="col-6">
                <div class="bg-white rounded-3 border p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:{{ $s['color'] }}18;">
                        <i class="bi {{ $s['icon'] }}" style="color:{{ $s['color'] }};"></i>
                    </div>
                    <div><div class="fw-bold">{{ $s['value'] }}</div><div class="text-muted" style="font-size:.75rem;">{{ $s['label'] }}</div></div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Recent Invoices</h6>
            <table class="table table-sm align-middle mb-0" style="font-size:.85rem;">
                <thead class="table-light"><tr><th>Invoice #</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    @forelse($user->invoices()->latest()->take(5)->get() as $inv)
                    <tr>
                        <td class="fw-semibold">{{ $inv->invoice_number }}</td>
                        <td>$ {{ number_format($inv->total) }}</td>
                        <td>
                            @php $c=match($inv->status){'paid'=>'success','unpaid'=>'warning','overdue'=>'danger',default=>'secondary'}; @endphp
                            <span class="badge bg-{{ $c }}-subtle text-{{ $c }}">{{ ucfirst($inv->status) }}</span>
                        </td>
                        <td class="text-muted">{{ $inv->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-muted text-center py-2">No invoices.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
