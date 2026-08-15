<?php

namespace App\Services;

use App\Models\TldPricing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DomainRegistrarService
{
    // Fallback hardcoded prices if DB table doesn't exist yet
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

    /**
     * Load TLD pricing from DB (cached 10 min), fall back to hardcoded array.
     */
    public function getTldPricing(): array
    {
        try {
            return Cache::remember('tld_pricing', 600, function () {
                $rows = TldPricing::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();

                if ($rows->isEmpty()) {
                    return $this->fallbackPricing;
                }

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
            // DB table may not exist yet — use fallback
            Log::warning('TLD pricing DB read failed, using fallback: ' . $e->getMessage());
            return $this->fallbackPricing;
        }
    }

    // WHOIS servers for each TLD
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

    /**
     * Check domain availability for all supported TLDs.
     */
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
                'currency'  => 'USD',
            ];
        }

        return $results;
    }

    /**
     * Check a single domain's availability using WHOIS.
     */
    public function checkSingleDomain(string $domain): bool
    {
        try {
            // Get TLD from domain
            $parts = explode('.', $domain, 2);
            if (count($parts) < 2) return false;

            $tld        = strtolower($parts[1]);
            $whoisHost  = $this->whoisServers[$tld] ?? $this->getWhoisServer($tld);

            if (!$whoisHost) {
                // Fallback: assume available if no WHOIS server
                return true;
            }

            $response = $this->queryWhois($domain, $whoisHost);

            if (empty($response)) {
                return true; // No response = likely available
            }

            // Check response for "not found" / availability indicators
            $notFoundPatterns = [
                'no match',
                'not found',
                'no entries found',
                'domain not found',
                'status: free',
                'is available',
                'no data found',
                'object does not exist',
                'no information available',
                'available for registration',
            ];

            $responseLower = strtolower($response);
            foreach ($notFoundPatterns as $pattern) {
                if (str_contains($responseLower, $pattern)) {
                    return true; // Available
                }
            }

            // If response contains registration data, domain is taken
            $takenPatterns = [
                'registrar:',
                'registrant:',
                'creation date:',
                'created:',
                'registered:',
                'domain name:',
                'registry domain id:',
            ];

            foreach ($takenPatterns as $pattern) {
                if (str_contains($responseLower, $pattern)) {
                    return false; // Taken
                }
            }

            return true; // Default to available if unclear

        } catch (\Throwable $e) {
            Log::warning("WHOIS check failed for {$domain}: " . $e->getMessage());
            return true; // Default available on error
        }
    }

    /**
     * Query a WHOIS server via socket.
     */
    protected function queryWhois(string $domain, string $server): string
    {
        $socket = @fsockopen($server, 43, $errno, $errstr, 10);

        if (!$socket) {
            Log::warning("WHOIS socket failed for {$server}: {$errstr}");
            return '';
        }

        fwrite($socket, $domain . "\r\n");

        $response = '';
        while (!feof($socket)) {
            $response .= fread($socket, 1024);
        }
        fclose($socket);

        return $response;
    }

    /**
     * Get WHOIS server for a TLD dynamically.
     */
    protected function getWhoisServer(string $tld): ?string
    {
        // Query IANA for the WHOIS server
        try {
            $response = $this->queryWhois($tld, 'whois.iana.org');
            if (preg_match('/whois:\s+(.+)/i', $response, $matches)) {
                return trim($matches[1]);
            }
        } catch (\Throwable $e) {}

        return null;
    }

    /**
     * Register a domain.
     */
    public function registerDomain(string $domain, array $contactInfo): array
    {
        Log::info("Domain registration requested: {$domain}");
        return ['success' => true, 'registrar_id' => 'REG-' . strtoupper(uniqid())];
    }

    /**
     * Renew a domain.
     */
    public function renewDomain(string $domain, int $years = 1): array
    {
        Log::info("Domain renewal requested: {$domain} for {$years} year(s)");
        return ['success' => true];
    }

    /**
     * Transfer a domain.
     */
    public function transferDomain(string $domain, string $eppCode): array
    {
        Log::info("Domain transfer requested: {$domain}");
        return ['success' => true];
    }
}
