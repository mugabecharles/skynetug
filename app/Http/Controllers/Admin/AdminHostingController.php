<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\HostingAccount;
use App\Services\CpanelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminHostingController extends Controller
{
    public function __construct(protected CpanelService $cpanel) {}

    public function index(Request $request)
    {
        $query = HostingAccount::with(['user', 'hostingPackage', 'server'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('domain', 'like', "%$s%")->orWhere('username', 'like', "%$s%"));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $accounts = $query->paginate(20)->withQueryString();
        return view('admin.hosting.index', compact('accounts'));
    }

    public function show(int $id)
    {
        $account = HostingAccount::with(['user', 'hostingPackage', 'server', 'sslCertificates', 'emailAccounts'])->findOrFail($id);
        return view('admin.hosting.show', compact('account'));
    }

    public function suspend(int $id)
    {
        $account = HostingAccount::findOrFail($id);
        $this->cpanel->suspendAccount($account->username, 'Manually suspended by admin');
        $account->update(['status' => 'suspended', 'suspended_at' => now()]);

        AuditLog::create([
            'user_id'       => Auth::id(),
            'action_type'   => 'suspend_hosting',
            'resource_type' => 'hosting_account',
            'resource_id'   => $account->id,
            'description'   => "Manually suspended hosting account: {$account->domain}",
            'ip_address'    => request()->ip(),
        ]);

        return back()->with('success', "Account {$account->domain} suspended.");
    }

    public function unsuspend(int $id)
    {
        $account = HostingAccount::findOrFail($id);
        $this->cpanel->unsuspendAccount($account->username);
        $account->update(['status' => 'active', 'suspended_at' => null, 'suspension_reason' => null]);

        AuditLog::create([
            'user_id'       => Auth::id(),
            'action_type'   => 'unsuspend_hosting',
            'resource_type' => 'hosting_account',
            'resource_id'   => $account->id,
            'description'   => "Unsuspended hosting account: {$account->domain}",
            'ip_address'    => request()->ip(),
        ]);

        return back()->with('success', "Account {$account->domain} unsuspended.");
    }

    public function terminate(int $id)
    {
        $account = HostingAccount::findOrFail($id);
        $this->cpanel->terminateAccount($account->username);
        $account->update(['status' => 'terminated', 'termination_date' => now()->toDateString()]);

        AuditLog::create([
            'user_id'       => Auth::id(),
            'action_type'   => 'terminate_hosting',
            'resource_type' => 'hosting_account',
            'resource_id'   => $account->id,
            'description'   => "Terminated hosting account: {$account->domain}",
            'ip_address'    => request()->ip(),
        ]);

        return redirect()->route('admin.hosting.index')->with('success', "Account {$account->domain} terminated.");
    }
}
