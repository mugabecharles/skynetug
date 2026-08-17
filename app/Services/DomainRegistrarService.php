<?php

namespace App\Services;

use App\Models\TldPricing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DomainRegistrarService
{
    // ── Fallback hardcoded prices (UGX) if DB is empty ───────────────────
    protected array $fallbackPricing = [
        '.com'   => ['register' => 35000, 'renew' => 35000],
        '.net'   => ['register' => 30000, 'renew' => 30000],
        '.org'   => ['register' => 28000, 'renew' => 28000],
        '.biz'   => ['register' => 32000, 'renew' => 32000],
        '.info'  => ['register' => 25000, 'renew' => 25000],
        '.ug'    => ['register' => 55000, 'renew' => 55000],
        '.co.ug' => ['register' => 25000, 'renew' => 25000],
        '.ac.ug' => ['register' => 25000, 'renew' => 25000],
        '.or.ug' => ['register' => 25000, 'renew' => 25000],
    ];

    // ── WHOIS servers ────────────────────────────────────────────────────
    protected array $whoisServers = [
        'com'   => 'whois.verisign-grs.com',
        'net'   => 'whois.verisign-grs.com',
        'org'   => 'whois.pir.org',
        'biz'   => 'whois.biz',
        'info'  => 'whois.afilias.net',
        'ug'    => 'whois.co.ug',
        'co.ug' => 'whois.co.ug',
        'ac.ug' => 'whois.co.ug',
        'or.ug' => 'whois.co.ug',
    ];

    // ── Registrar config ─────────────────────────────────────────────────
    protected string  $resellerId;
    protected string  $apiKey;
    protected string  $baseUrl;
    protected bool    $configured;

    public function __construct()
    {
        $this->resellerId = (string) config('services.resellerclub.reseller_id', '');
        $this->apiKey     = (string) config('services.resellerclub.api_key', '');
        $this->baseUrl    = (string) config('services.resellerclub.base_url', 'https://httpapi.com/api');
        $this->configured = !empty($this->resellerId) && !empty($this->apiKey);
    }

    // =========================================================================
    // TLD PRICING
    // =========================================================================

    public function getTldPricing(): array
    {
        try {
            return Cache::remember('tld_pricing', 600, function () {
                $rows = TldPricing::where('is_active', true)->orderBy('sort_order')->get();
                if ($rows->isEmpty()) return $this->fallbackPricing;

                $pricing = [];
                foreach ($rows as $row) {
                    $pricing[$row->tld] = [
                        'register' => (float) $row->register_price,
                        'renew'    => (float) $row->renew_price,
                        'transfer' => (float) $row->transfer_price,
                        'popular'  => (bool)  $row->is_popular,
                    ];
                }
                return $pricing;
            });
        } catch (\Throwable $e) {
            Log::warning('TLD pricing DB read failed, using fallback: ' . $e->getMessage());
            return $this->fallbackPricing;
        }
    }

    // =========================================================================
    // AVAILABILITY CHECK
    // =========================================================================

    public function checkAvailability(string $sld): array
    {
        $results = [];
        $pricing = $this->getTldPricing();

        foreach ($pricing as $tld => $data) {
            $fullDomain = $sld . $tld;
            $available  = $this->checkSingleDomain($fullDomain);

            $results[] = [
                'domain'    => $fullDomain,
                'tld'       => $tld,
                'available' => $available,
                'price'     => $data['register'],
                'currency'  => 'UGX',
            ];
        }

        return $results;
    }

    public function checkSingleDomain(string $domain): bool
    {
        // Try ResellerClub API first if configured (more reliable than WHOIS)
        if ($this->configured) {
            return $this->checkAvailabilityViaApi($domain);
        }
        return $this->checkSingleDomainWhois($domain);
    }

    protected function checkAvailabilityViaApi(string $domain): bool
    {
        try {
            $parts = explode('.', $domain, 2);
            if (count($parts) < 2) return false;

            $tld = $parts[1];

            $response = $this->rcGet('/domains/available', [
                'domain-name' => [$parts[0]],
                'tlds'        => [$tld],
            ]);

            $status = $response["{$parts[0]}.{$tld}"]['status'] ?? '';
            return strtolower($status) === 'available';
        } catch (\Throwable $e) {
            Log::warning("ResellerClub availability check failed for {$domain}: " . $e->getMessage());
            return $this->checkSingleDomainWhois($domain);
        }
    }

    protected function checkSingleDomainWhois(string $domain): bool
    {
        try {
            $parts     = explode('.', $domain, 2);
            $tld       = strtolower($parts[1] ?? '');
            $whoisHost = $this->whoisServers[$tld] ?? $this->getWhoisServer($tld);

            if (!$whoisHost) return true;

            $response = $this->queryWhois($domain, $whoisHost);
            if (empty($response)) return true;

            $lower = strtolower($response);

            foreach (['no match','not found','no entries found','domain not found',
                      'status: free','is available','no data found',
                      'object does not exist','available for registration'] as $p) {
                if (str_contains($lower, $p)) return true;
            }
            foreach (['registrar:','registrant:','creation date:','created:',
                      'registered:','domain name:','registry domain id:'] as $p) {
                if (str_contains($lower, $p)) return false;
            }
            return true;
        } catch (\Throwable $e) {
            Log::warning("WHOIS check failed for {$domain}: " . $e->getMessage());
            return true;
        }
    }

    // =========================================================================
    // DOMAIN REGISTRATION
    // =========================================================================

    public function registerDomain(string $domain, array $contactInfo): array
    {
        if (!$this->configured) {
            Log::warning("DomainRegistrar not configured — simulating registration for {$domain}");
            return ['success' => true, 'registrar_id' => 'SIM-' . strtoupper(uniqid())];
        }

        try {
            // Step 1: Create / find customer contact
            $contactId = $this->getOrCreateContact($contactInfo);

            if (!$contactId) {
                return ['success' => false, 'error' => 'Could not create registrant contact.'];
            }

            // Step 2: Register the domain
            $parts = explode('.', $domain, 2);
            $tld   = $parts[1] ?? 'com';
            $years = 1;

            $response = $this->rcPost('/domains/register', [
                'domain-name'          => $domain,
                'years'                => $years,
                'ns'                   => ['ns1.skynetug.com', 'ns2.skynetug.com'],
                'registrant-contact-id'=> $contactId,
                'admin-contact-id'     => $contactId,
                'tech-contact-id'      => $contactId,
                'billing-contact-id'   => $contactId,
                'invoice-option'       => 'NoInvoice',
                'protect-privacy'      => false,
                'purchase-privacy'     => false,
            ]);

            if (isset($response['entityid'])) {
                Log::info("Domain registered via ResellerClub: {$domain} (ID: {$response['entityid']})");
                return [
                    'success'      => true,
                    'registrar_id' => (string) $response['entityid'],
                ];
            }

            $error = $response['message'] ?? $response['error'] ?? json_encode($response);
            Log::error("ResellerClub registration failed for {$domain}: {$error}");
            return ['success' => false, 'error' => $error];

        } catch (\Throwable $e) {
            Log::error("Domain registration exception for {$domain}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // =========================================================================
    // DOMAIN RENEWAL
    // =========================================================================

    public function renewDomain(string $domain, int $years = 1): array
    {
        if (!$this->configured) {
            Log::warning("DomainRegistrar not configured — simulating renewal for {$domain}");
            return ['success' => true];
        }

        try {
            // Look up the order ID
            $info = $this->getDomainInfo($domain);
            if (!$info) {
                return ['success' => false, 'error' => 'Domain not found in registrar.'];
            }

            $orderId      = $info['entityid'];
            $currentExpiry= $info['endtime'] ?? null;

            $response = $this->rcPost('/domains/renew', [
                'order-id'       => $orderId,
                'years'          => $years,
                'exp-date'       => $currentExpiry,
                'invoice-option' => 'NoInvoice',
            ]);

            if (isset($response['entityid'])) {
                Log::info("Domain renewed via ResellerClub: {$domain} for {$years} year(s)");
                return ['success' => true];
            }

            $error = $response['message'] ?? json_encode($response);
            Log::error("ResellerClub renewal failed for {$domain}: {$error}");
            return ['success' => false, 'error' => $error];

        } catch (\Throwable $e) {
            Log::error("Domain renewal exception for {$domain}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // =========================================================================
    // DOMAIN TRANSFER
    // =========================================================================

    public function transferDomain(string $domain, string $eppCode): array
    {
        if (!$this->configured) {
            Log::warning("DomainRegistrar not configured — simulating transfer for {$domain}");
            return ['success' => true];
        }

        try {
            $response = $this->rcPost('/domains/transfer', [
                'domain-name'    => $domain,
                'auth-code'      => $eppCode,
                'ns'             => ['ns1.skynetug.com', 'ns2.skynetug.com'],
                'invoice-option' => 'NoInvoice',
            ]);

            if (isset($response['entityid'])) {
                Log::info("Domain transfer initiated via ResellerClub: {$domain}");
                return ['success' => true, 'registrar_id' => (string) $response['entityid']];
            }

            $error = $response['message'] ?? json_encode($response);
            Log::error("ResellerClub transfer failed for {$domain}: {$error}");
            return ['success' => false, 'error' => $error];

        } catch (\Throwable $e) {
            Log::error("Domain transfer exception for {$domain}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // =========================================================================
    // DOMAIN INFO
    // =========================================================================

    public function getDomainInfo(string $domain): ?array
    {
        if (!$this->configured) return null;

        try {
            $response = $this->rcGet('/domains/details-by-name', [
                'domain-name' => $domain,
                'options'     => 'All',
            ]);

            return isset($response['entityid']) ? $response : null;
        } catch (\Throwable $e) {
            Log::warning("getDomainInfo failed for {$domain}: " . $e->getMessage());
            return null;
        }
    }

    // =========================================================================
    // CONTACT MANAGEMENT
    // =========================================================================

    protected function getOrCreateContact(array $info): ?int
    {
        // Parse name into first/last
        $nameParts = explode(' ', trim($info['name'] ?? 'SkyNetug Customer'), 2);
        $firstName = $nameParts[0];
        $lastName  = $nameParts[1] ?? $nameParts[0];

        $phone = preg_replace('/[^0-9+]/', '', $info['phone'] ?? '+256700000000');
        if (!str_starts_with($phone, '+')) {
            $phone = '+256' . ltrim($phone, '0');
        }
        // ResellerClub phone format: +CountryCode.Number
        $phone = preg_replace('/^\+(\d{3})(\d+)$/', '+$1.$2', $phone);

        $response = $this->rcPost('/contacts/add', [
            'name'           => $info['name'] ?? 'SkyNetug Customer',
            'company'        => $info['company'] ?? 'N/A',
            'email'          => $info['email'],
            'address-line-1' => $info['address'] ?? 'Kampala, Uganda',
            'city'           => $info['city'] ?? 'Kampala',
            'state'          => $info['state'] ?? 'Central',
            'zipcode'        => $info['postcode'] ?? '00256',
            'country'        => strtoupper($info['country'] ?? 'UG'),
            'phone-cc'       => '256',
            'phone'          => ltrim(str_replace('+256.', '', $phone), '0'),
            'type'           => 'Contact',
        ]);

        return isset($response['contactid']) ? (int) $response['contactid'] : null;
    }

    // =========================================================================
    // RESELLERCLUB HTTP HELPERS
    // =========================================================================

    protected function rcGet(string $endpoint, array $params = []): array
    {
        $params['auth-userid'] = $this->resellerId;
        $params['api-key']     = $this->apiKey;

        $response = Http::timeout(30)
            ->get($this->baseUrl . $endpoint . '.json', $params);

        return $response->json() ?? [];
    }

    protected function rcPost(string $endpoint, array $params = []): array
    {
        $params['auth-userid'] = $this->resellerId;
        $params['api-key']     = $this->apiKey;

        $response = Http::timeout(30)
            ->asForm()
            ->post($this->baseUrl . $endpoint . '.json', $params);

        return $response->json() ?? [];
    }

    // =========================================================================
    // WHOIS HELPERS
    // =========================================================================

    protected function queryWhois(string $domain, string $server): string
    {
        $socket = @fsockopen($server, 43, $errno, $errstr, 10);
        if (!$socket) return '';

        fwrite($socket, $domain . "\r\n");
        $response = '';
        while (!feof($socket)) {
            $response .= fread($socket, 1024);
        }
        fclose($socket);
        return $response;
    }

    protected function getWhoisServer(string $tld): ?string
    {
        try {
            $response = $this->queryWhois($tld, 'whois.iana.org');
            if (preg_match('/whois:\s+(.+)/i', $response, $matches)) {
                return trim($matches[1]);
            }
        } catch (\Throwable $e) {}
        return null;
    }
}
