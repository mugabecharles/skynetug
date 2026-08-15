<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\DnsRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainManagementController extends Controller
{
    public function index()
    {
        $domains = Auth::user()->domains()->latest()->paginate(15);
        return view('dashboard.domains.index', compact('domains'));
    }

    public function show(int $id)
    {
        $domain = Auth::user()->domains()
            ->with('dnsRecords')
            ->findOrFail($id);

        return view('dashboard.domains.show', compact('domain'));
    }

    public function updateDns(Request $request, int $id)
    {
        $domain = Auth::user()->domains()->findOrFail($id);

        $request->validate([
            'records'            => ['required', 'array'],
            'records.*.type'     => ['required', 'in:A,AAAA,CNAME,MX,TXT,NS,SRV'],
            'records.*.name'     => ['required', 'string', 'max:255'],
            'records.*.value'    => ['required', 'string'],
            'records.*.ttl'      => ['nullable', 'integer', 'min:60'],
            'records.*.priority' => ['nullable', 'integer'],
        ]);

        $domain->dnsRecords()->delete();

        foreach ($request->records as $record) {
            DnsRecord::create([
                'domain_id' => $domain->id,
                'type'      => $record['type'],
                'name'      => $record['name'],
                'value'     => $record['value'],
                'ttl'       => $record['ttl'] ?? 3600,
                'priority'  => $record['priority'] ?? 0,
            ]);
        }

        return back()->with('success', 'DNS records updated successfully.');
    }

    public function updateNameservers(Request $request, int $id)
    {
        $domain = Auth::user()->domains()->findOrFail($id);

        $request->validate([
            'ns1' => ['required', 'string', 'max:255'],
            'ns2' => ['required', 'string', 'max:255'],
            'ns3' => ['nullable', 'string', 'max:255'],
            'ns4' => ['nullable', 'string', 'max:255'],
        ]);

        $domain->update([
            'nameserver_1' => $request->ns1,
            'nameserver_2' => $request->ns2,
            'nameserver_3' => $request->ns3,
            'nameserver_4' => $request->ns4,
        ]);

        return back()->with('success', 'Nameservers updated. Changes may take up to 48 hours to propagate.');
    }

    public function toggleLock(Request $request, int $id)
    {
        $domain = Auth::user()->domains()->findOrFail($id);
        $domain->update(['is_locked' => !$domain->is_locked]);

        $status = $domain->is_locked ? 'locked' : 'unlocked';
        return back()->with('success', "Domain has been {$status} successfully.");
    }

    public function togglePrivacy(Request $request, int $id)
    {
        $domain = Auth::user()->domains()->findOrFail($id);
        $domain->update(['whois_privacy' => !$domain->whois_privacy]);

        $status = $domain->whois_privacy ? 'enabled' : 'disabled';
        return back()->with('success', "WHOIS Privacy has been {$status} successfully.");
    }
}
