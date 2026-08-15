<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;

class AdminDomainController extends Controller
{
    public function index(Request $request)
    {
        $query = Domain::with('user')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('domain_name', 'like', "%$s%");
        }
        if ($request->filled('tld'))    { $query->where('tld', $request->tld); }
        if ($request->filled('status')) { $query->where('status', $request->status); }

        $domains = $query->paginate(25)->withQueryString();
        return view('admin.domains.index', compact('domains'));
    }

    public function show(int $id)
    {
        $domain = Domain::with(['user', 'dnsRecords'])->findOrFail($id);
        return view('admin.domains.show', compact('domain'));
    }
}
