<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\HostingAccount;
use App\Services\CpanelService;
use App\Services\SoftaculousService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostingAccountController extends Controller
{
    public function __construct(
        protected CpanelService      $cpanel,
        protected SoftaculousService $softaculous,
    ) {}

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

        // Load Softaculous installed apps (empty array if account not active or not configured)
        $installedApps = $account->status === 'active'
            ? $this->softaculous->listInstalled($account->username)
            : [];

        return view('dashboard.hosting.show', compact('account', 'installedApps'));
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
