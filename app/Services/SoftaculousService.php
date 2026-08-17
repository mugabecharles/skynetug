<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Softaculous integration via cPanel UAPI.
 *
 * Softaculous exposes its API through cPanel's UAPI at:
 *   https://<cpanel-host>:2083/execute/Softaculous/<action>
 *
 * Authentication uses the same WHM API token (passed as cPanel user token
 * on behalf of each hosting account username).
 */
class SoftaculousService
{
    protected string $host;
    protected int    $port;
    protected string $rootUsername;
    protected string $apiToken;
    protected bool   $configured;

    public function __construct()
    {
        $this->host         = (string) config('services.softaculous.host', '');
        $this->port         = (int)   config('services.softaculous.port', 2087);
        $this->rootUsername = (string) config('services.softaculous.username', 'root');
        $this->apiToken     = (string) config('services.softaculous.api_token', '');
        $this->configured   = !empty($this->host) && !empty($this->apiToken);
    }

    // =========================================================================
    // LIST INSTALLED SCRIPTS
    // Returns array of installed apps for a cPanel username
    // =========================================================================

    public function listInstalled(string $cpanelUser): array
    {
        if (!$this->configured) {
            Log::info("Softaculous not configured — returning empty installs for {$cpanelUser}");
            return [];
        }

        try {
            $response = $this->callUapi($cpanelUser, 'Softaculous', 'listinstalled');

            $installs = $response['result']['data']['installs'] ?? [];

            return array_map(function ($install) {
                return [
                    'install_id'   => $install['insid']       ?? null,
                    'app_name'     => $install['softname']    ?? 'Unknown',
                    'version'      => $install['ver']         ?? '-',
                    'install_dir'  => $install['installdir']  ?? '-',
                    'install_url'  => $install['siteurl']     ?? null,
                    'domain'       => $install['domain']      ?? null,
                    'installed_at' => $install['install_date']?? null,
                ];
            }, array_values($installs));

        } catch (\Throwable $e) {
            Log::error("Softaculous listInstalled failed for {$cpanelUser}: " . $e->getMessage());
            return [];
        }
    }

    // =========================================================================
    // LIST AVAILABLE SCRIPTS
    // Returns the catalog of installable apps
    // =========================================================================

    public function listAvailable(string $cpanelUser): array
    {
        if (!$this->configured) {
            return $this->mockedAppList();
        }

        try {
            $response = $this->callUapi($cpanelUser, 'Softaculous', 'scripts');
            $scripts  = $response['result']['data']['scripts'] ?? [];

            $apps = [];
            foreach ($scripts as $sid => $script) {
                $apps[] = [
                    'sid'         => $sid,
                    'name'        => $script['name']     ?? 'Unknown',
                    'type'        => $script['type']     ?? 'misc',
                    'description' => $script['desc']     ?? '',
                    'version'     => $script['version']  ?? '',
                    'icon'        => $script['logo_url'] ?? null,
                ];
            }

            return $apps;

        } catch (\Throwable $e) {
            Log::error("Softaculous listAvailable failed: " . $e->getMessage());
            return $this->mockedAppList();
        }
    }

    // =========================================================================
    // INSTALL A SCRIPT
    // =========================================================================

    public function install(string $cpanelUser, array $params): array
    {
        if (!$this->configured) {
            Log::warning("Softaculous not configured — cannot install for {$cpanelUser}");
            return ['success' => false, 'error' => 'Softaculous is not configured on this server.'];
        }

        try {
            // Required params: softid, softdomain, softdirectory, admin_username, admin_pass, admin_email, site_name
            $response = $this->callUapi($cpanelUser, 'Softaculous', 'install', $params);

            if (($response['result']['status'] ?? 0) === 1) {
                $installId = $response['result']['data']['insid'] ?? null;
                Log::info("Softaculous install success for {$cpanelUser}: install_id={$installId}");
                return ['success' => true, 'install_id' => $installId];
            }

            $errors = $response['result']['errors'] ?? ['Installation failed'];
            $msg    = is_array($errors) ? implode(', ', $errors) : (string) $errors;
            Log::error("Softaculous install failed for {$cpanelUser}: {$msg}");
            return ['success' => false, 'error' => $msg];

        } catch (\Throwable $e) {
            Log::error("Softaculous install exception for {$cpanelUser}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // =========================================================================
    // UPGRADE A SCRIPT
    // =========================================================================

    public function upgrade(string $cpanelUser, int $installId): array
    {
        if (!$this->configured) {
            return ['success' => false, 'error' => 'Softaculous is not configured on this server.'];
        }

        try {
            $response = $this->callUapi($cpanelUser, 'Softaculous', 'upgrade', [
                'insid' => $installId,
            ]);

            if (($response['result']['status'] ?? 0) === 1) {
                Log::info("Softaculous upgrade success: install_id={$installId}");
                return ['success' => true];
            }

            $errors = $response['result']['errors'] ?? ['Upgrade failed'];
            $msg    = is_array($errors) ? implode(', ', $errors) : (string) $errors;
            return ['success' => false, 'error' => $msg];

        } catch (\Throwable $e) {
            Log::error("Softaculous upgrade exception: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // =========================================================================
    // REMOVE AN INSTALLED SCRIPT
    // =========================================================================

    public function remove(string $cpanelUser, int $installId): array
    {
        if (!$this->configured) {
            return ['success' => false, 'error' => 'Softaculous is not configured on this server.'];
        }

        try {
            $response = $this->callUapi($cpanelUser, 'Softaculous', 'remove', [
                'insid' => $installId,
            ]);

            if (($response['result']['status'] ?? 0) === 1) {
                Log::info("Softaculous remove success: install_id={$installId}");
                return ['success' => true];
            }

            $errors = $response['result']['errors'] ?? ['Removal failed'];
            $msg    = is_array($errors) ? implode(', ', $errors) : (string) $errors;
            return ['success' => false, 'error' => $msg];

        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // =========================================================================
    // CPANEL UAPI HTTP CALL
    // Uses WHM reseller token to impersonate the cPanel user
    // =========================================================================

    protected function callUapi(string $cpanelUser, string $module, string $function, array $params = []): array
    {
        // WHM API token auth allows calling cPanel UAPI on behalf of any user
        $url = "https://{$this->host}:{$this->port}/json-api/cpanel";

        $payload = array_merge([
            'cpanel_jsonapi_user'    => $cpanelUser,
            'cpanel_jsonapi_apiversion' => 3,
            'cpanel_jsonapi_module'  => $module,
            'cpanel_jsonapi_func'    => $function,
        ], $params);

        $response = Http::withHeaders([
            'Authorization' => "WHM {$this->rootUsername}:{$this->apiToken}",
        ])
            ->withoutVerifying()
            ->timeout(30)
            ->get($url, $payload);

        return $response->json() ?? [];
    }

    // =========================================================================
    // MOCKED APP LIST (when Softaculous not configured)
    // =========================================================================

    protected function mockedAppList(): array
    {
        return [
            ['sid' => 26,  'name' => 'WordPress',         'type' => 'blog',    'description' => 'The most popular blogging and CMS platform.',          'version' => '6.x', 'icon' => null],
            ['sid' => 169, 'name' => 'Joomla',            'type' => 'cms',     'description' => 'A flexible, open-source CMS.',                         'version' => '4.x', 'icon' => null],
            ['sid' => 2,   'name' => 'Drupal',            'type' => 'cms',     'description' => 'Enterprise-grade open source CMS.',                    'version' => '10.x','icon' => null],
            ['sid' => 95,  'name' => 'PrestaShop',        'type' => 'ecomm',   'description' => 'Free, open-source e-commerce solution.',               'version' => '8.x', 'icon' => null],
            ['sid' => 36,  'name' => 'OpenCart',          'type' => 'ecomm',   'description' => 'Simple, powerful online store solution.',              'version' => '4.x', 'icon' => null],
            ['sid' => 114, 'name' => 'Magento',           'type' => 'ecomm',   'description' => 'Enterprise e-commerce platform.',                      'version' => '2.x', 'icon' => null],
            ['sid' => 450, 'name' => 'phpBB',             'type' => 'forum',   'description' => 'Popular open-source forum software.',                  'version' => '3.x', 'icon' => null],
            ['sid' => 272, 'name' => 'Moodle',            'type' => 'edu',     'description' => 'Learning management system (LMS).',                    'version' => '4.x', 'icon' => null],
        ];
    }
}
