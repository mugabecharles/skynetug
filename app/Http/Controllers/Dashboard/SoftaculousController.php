<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\HostingAccount;
use App\Services\SoftaculousService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoftaculousController extends Controller
{
    public function __construct(protected SoftaculousService $softaculous) {}

    /**
     * List available apps for a hosting account.
     */
    public function available(int $accountId)
    {
        $account = Auth::user()->hostingAccounts()->findOrFail($accountId);
        $apps    = $this->softaculous->listAvailable($account->username);

        return response()->json(['apps' => $apps]);
    }

    /**
     * Install an application.
     */
    public function install(Request $request, int $accountId)
    {
        $account = Auth::user()->hostingAccounts()
            ->where('status', 'active')
            ->findOrFail($accountId);

        $request->validate([
            'softid'        => ['required', 'integer'],
            'site_name'     => ['required', 'string', 'max:100'],
            'directory'     => ['nullable', 'string', 'max:100'],
            'admin_username'=> ['required', 'string', 'max:50'],
            'admin_password'=> ['required', 'string', 'min:6'],
            'admin_email'   => ['required', 'email'],
        ]);

        $result = $this->softaculous->install($account->username, [
            'softid'          => $request->softid,
            'softdomain'      => $account->domain,
            'softdirectory'   => $request->directory ?? '',
            'site_name'       => $request->site_name,
            'admin_username'  => $request->admin_username,
            'admin_pass'      => $request->admin_password,
            'admin_email'     => $request->admin_email,
        ]);

        if ($result['success']) {
            return back()->with('success', 'Application installed successfully! It will be live within a few minutes.');
        }

        return back()->with('error', 'Installation failed: ' . ($result['error'] ?? 'Unknown error.'));
    }

    /**
     * Upgrade an installed application to latest version.
     */
    public function upgrade(Request $request, int $accountId, int $installId)
    {
        $account = Auth::user()->hostingAccounts()
            ->where('status', 'active')
            ->findOrFail($accountId);

        $result = $this->softaculous->upgrade($account->username, $installId);

        if ($result['success']) {
            return back()->with('success', 'Application upgraded to the latest version.');
        }

        return back()->with('error', 'Upgrade failed: ' . ($result['error'] ?? 'Unknown error.'));
    }

    /**
     * Remove an installed application.
     */
    public function remove(Request $request, int $accountId, int $installId)
    {
        $account = Auth::user()->hostingAccounts()
            ->where('status', 'active')
            ->findOrFail($accountId);

        $result = $this->softaculous->remove($account->username, $installId);

        if ($result['success']) {
            return back()->with('success', 'Application removed successfully.');
        }

        return back()->with('error', 'Removal failed: ' . ($result['error'] ?? 'Unknown error.'));
    }
}
