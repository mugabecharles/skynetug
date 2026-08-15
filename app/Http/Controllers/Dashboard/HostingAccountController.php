<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\HostingAccount;
use App\Services\CpanelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostingAccountController extends Controller
{
    public function __construct(protected CpanelService $cpanel) {}

    public function index()
    {
        $accounts = Auth::user()->hostingAccounts()
            ->with(['hostingPackage', 'server'])
            ->latest()
            ->paginate(10);

        return view('dashboard.hosting.index', compact('accounts'));
    }

    public function show(int $id)
    {
        $account = Auth::user()->hostingAccounts()
            ->with(['hostingPackage', 'server', 'sslCertificates', 'emailAccounts', 'backups'])
            ->findOrFail($id);

        return view('dashboard.hosting.show', compact('account'));
    }

    public function cpanelSso(int $id)
    {
        $account = Auth::user()->hostingAccounts()->findOrFail($id);

        if ($account->status !== 'active') {
            return back()->with('error', 'This hosting account is not active.');
        }

        try {
            $ssoUrl = $this->cpanel->getSsoUrl($account->username);
            return redirect($ssoUrl);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to open cPanel at this time. Please try again later.');
        }
    }
}
