@extends('layouts.dashboard')
@section('page_title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Active Hosting',  'value'=>$activeHosting,  'icon'=>'bi-server',      'color'=>'#0066FF','bg'=>'#EFF6FF','link'=>route('dashboard.hosting.index')],
        ['label'=>'Active Domains',  'value'=>$activeDomains,  'icon'=>'bi-link-45deg',  'color'=>'#059669','bg'=>'#ECFDF5','link'=>route('dashboard.domains.index')],
        ['label'=>'Unpaid Invoices', 'value'=>$unpaidInvoices, 'icon'=>'bi-receipt',      'color'=>'#D97706','bg'=>'#FFFBEB','link'=>route('dashboard.invoices.index')],
        ['label'=>'Open Tickets',    'value'=>$openTickets,    'icon'=>'bi-headset',      'color'=>'#DC2626','bg'=>'#FEF2F2','link'=>route('dashboard.tickets.index')],
    ] as $stat)
    <div class="col-6 col-md-3">
        <a href="{{ $stat['link'] }}" style="text-decoration:none;">
            <div class="stat-card">
                <div class="stat-icon" style="background:{{ $stat['bg'] }};color:{{ $stat['color'] }};">
                    <i class="bi {{ $stat['icon'] }}"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stat['value'] }}</div>
                    <div class="stat-label">{{ $stat['label'] }}</div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<div class="row g-3">
    {{-- Recent Invoices --}}
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header">
                <h6><i class="bi bi-receipt me-2" style="color:#0066FF;"></i>Recent Invoices</h6>
                <a href="{{ route('dashboard.invoices.index') }}" style="font-size:12px;color:#0066FF;text-decoration:none;">View All →</a>
            </div>
            @if($recentInvoices->isEmpty())
                <div style="text-align:center;padding:40px;color:#9CA3AF;">
                    <i class="bi bi-receipt d-block mb-2" style="font-size:2.5rem;opacity:.3;"></i>
                    <div style="font-size:13px;">No invoices yet.</div>
                </div>
            @else
            <div class="data-table">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Invoice #</th><th>Amount</th><th>Status</th><th>Due</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentInvoices as $invoice)
                        <tr>
                            <td style="font-weight:600;">{{ $invoice->invoice_number }}</td>
                            <td>$ {{ number_format($invoice->total) }}</td>
                            <td>
                                @php $colors=['paid'=>['#ECFDF5','#15803D'],'unpaid'=>['#FFFBEB','#B45309'],'overdue'=>['#FEF2F2','#DC2626']]; $c=$colors[$invoice->status]??['#F3F4F6','#374151']; @endphp
                                <span class="badge badge-pill" style="background:{{ $c[0] }};color:{{ $c[1] }};">{{ ucfirst($invoice->status) }}</span>
                            </td>
                            <td style="color:#6B7280;font-size:12px;">{{ \Carbon\Carbon::parse($invoice->date_due)->format('d M Y') }}</td>
                            <td>
                                @if(in_array($invoice->status,['unpaid','overdue']))
                                    <a href="{{ route('dashboard.invoices.show',$invoice->id) }}" style="background:#0066FF;color:#fff;border-radius:6px;padding:4px 12px;font-size:12px;font-weight:600;text-decoration:none;">Pay</a>
                                @else
                                    <a href="{{ route('dashboard.invoices.show',$invoice->id) }}" style="border:1px solid #E8ECF0;color:#374151;border-radius:6px;padding:4px 12px;font-size:12px;text-decoration:none;">View</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Announcements --}}
        <div class="panel mb-3">
            <div class="panel-header">
                <h6><i class="bi bi-megaphone me-2" style="color:#0066FF;"></i>Announcements</h6>
            </div>
            <div class="panel-body" style="padding:12px 16px;">
                @forelse($announcements as $ann)
                <div style="border-bottom:1px solid #F3F4F6;padding:10px 0;">
                    <div style="font-weight:600;font-size:13px;margin-bottom:3px;">{{ $ann->title }}</div>
                    <div style="font-size:12px;color:#6B7280;line-height:1.5;">{{ Str::limit($ann->content,80) }}</div>
                    <div style="font-size:11px;color:#9CA3AF;margin-top:3px;">{{ $ann->published_at?->diffForHumans() }}</div>
                </div>
                @empty
                <p style="color:#9CA3AF;font-size:13px;text-align:center;padding:16px 0;margin:0;">No announcements.</p>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="panel">
            <div class="panel-header"><h6><i class="bi bi-lightning-charge me-2" style="color:#0066FF;"></i>Quick Actions</h6></div>
            <div class="panel-body">
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <a href="{{ route('dashboard.tickets.create') }}" style="background:#EFF6FF;color:#0066FF;border-radius:8px;padding:10px 14px;text-decoration:none;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;"><i class="bi bi-plus-circle"></i> Open Support Ticket</a>
                    <a href="{{ route('dashboard.invoices.index') }}" style="background:#FFFBEB;color:#B45309;border-radius:8px;padding:10px 14px;text-decoration:none;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;"><i class="bi bi-receipt"></i> View Invoices</a>
                    <a href="{{ route('hosting.shared') }}" style="background:#ECFDF5;color:#15803D;border-radius:8px;padding:10px 14px;text-decoration:none;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;"><i class="bi bi-server"></i> Add Hosting Plan</a>
                    <a href="{{ route('domains') }}" style="background:#F5F3FF;color:#7C3AED;border-radius:8px;padding:10px 14px;text-decoration:none;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;"><i class="bi bi-link-45deg"></i> Register Domain</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
