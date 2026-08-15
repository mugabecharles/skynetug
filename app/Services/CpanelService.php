<?php

namespace App\Services;

use App\Models\HostingAccount;
use App\Models\Server;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CpanelService
{
    protected string $host;
    protected int $port;
    protected string $username;
    protected string $apiToken;

    public function __construct()
    {
        $this->host     = config('services.cpanel.host', '');
        $this->port     = (int) config('services.cpanel.port', 2087);
        $this->username = config('services.cpanel.username', 'root');
        $this->apiToken = config('services.cpanel.api_token', '');
    }

    /**
     * Create a new cPanel hosting account.
     */
    public function createAccount(HostingAccount $account): array
    {
        $response = $this->call('POST', '/json-api/createacct', [
            'username' => $account->username,
            'domain'   => $account->domain,
            'plan'     => $account->hostingPackage->name ?? 'default',
            'password' => $account->cpanel_password,
            'contactemail' => $account->user->email,
        ]);

        if ($response['result']['status'] ?? false) {
            Log::info("cPanel account created: {$account->username}");
            return ['success' => true, 'data' => $response];
        }

        Log::error("cPanel account creation failed: " . json_encode($response));
        return ['success' => false, 'error' => $response['result']['statusmsg'] ?? 'Unknown error'];
    }

    /**
     * Suspend a cPanel account.
     */
    public function suspendAccount(string $username, string $reason = ''): bool
    {
        $response = $this->call('POST', '/json-api/suspendacct', [
            'user'   => $username,
            'reason' => $reason,
        ]);

        return (bool) ($response['result']['status'] ?? false);
    }

    /**
     * Unsuspend a cPanel account.
     */
    public function unsuspendAccount(string $username): bool
    {
        $response = $this->call('POST', '/json-api/unsuspendacct', [
            'user' => $username,
        ]);

        return (bool) ($response['result']['status'] ?? false);
    }

    /**
     * Terminate/remove a cPanel account.
     */
    public function terminateAccount(string $username): bool
    {
        $response = $this->call('POST', '/json-api/removeacct', [
            'user'          => $username,
            'keepdns'       => 0,
        ]);

        return (bool) ($response['result']['status'] ?? false);
    }

    /**
     * Get account disk and bandwidth usage.
     */
    public function getAccountUsage(string $username): array
    {
        $response = $this->call('GET', "/json-api/listaccts", [
            'searchtype' => 'user',
            'search'     => $username,
        ]);

        $account = $response['acct'][0] ?? null;

        if (!$account) {
            return ['disk_used' => 0, 'bandwidth_used' => 0];
        }

        return [
            'disk_used'      => (int) $account['diskused'] ?? 0,
            'bandwidth_used' => (int) $account['totalbytes'] ?? 0,
        ];
    }

    /**
     * Generate a single sign-on URL for cPanel.
     */
    public function getSsoUrl(string $username): string
    {
        $response = $this->call('GET', '/json-api/create_user_session', [
            'api.version' => 1,
            'user'        => $username,
            'service'     => 'cpaneld',
        ]);

        $url = $response['data']['url'] ?? null;

        if (!$url) {
            throw new \RuntimeException('Unable to generate cPanel SSO URL.');
        }

        return "https://{$this->host}:2083{$url}";
    }

    /**
     * Make an API request to the WHM server.
     */
    protected function call(string $method, string $endpoint, array $params = []): array
    {
        if (empty($this->host) || empty($this->apiToken)) {
            // Return mock data in development
            Log::info("cPanel API not configured. Mock response for: {$endpoint}");
            return ['result' => ['status' => 1, 'statusmsg' => 'OK']];
        }

        $baseUrl = "https://{$this->host}:{$this->port}";

        $response = Http::withHeaders([
            'Authorization' => "WHM {$this->username}:{$this->apiToken}",
        ])
            ->withoutVerifying()
            ->timeout(30)
            ->$method($baseUrl . $endpoint, $params);

        return $response->json() ?? [];
    }
}
