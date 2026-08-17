<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Services\DomainRegistrarService;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function __construct(protected DomainRegistrarService $registrar) {}

    public function index()
    {
        $tlds = [];
        foreach ($this->registrar->getTldPricing() as $tld => $pricing) {
            $tlds[] = [
                'tld'     => $tld,
                'price'   => $pricing['register'],
                'popular' => in_array($tld, ['.com', '.ug', '.co.ug']),
            ];
        }

        return view('marketing.domains', compact('tlds'));
    }

    public function search(Request $request)
    {
        $query = $request->get('domain', '');
        return view('marketing.domain-search', compact('query'));
    }

    public function check(Request $request)
    {
        $request->validate([
            'domain' => ['required', 'string', 'max:100'],
        ]);

        $input = strtolower(trim($request->domain));
        $input = preg_replace('/\.(com|net|org|biz|info|ug|co\.ug|ac\.ug|or\.ug)$/i', '', $input);
        $sld   = preg_replace('/[^a-z0-9\-]/', '', $input);

        if (empty($sld) || strlen($sld) < 2) {
            return response()->json(['error' => 'Please enter a valid domain name.'], 422);
        }

        $results = $this->registrar->checkAvailability($sld);

        return response()->json($results);
    }

    /**
     * Show the domain transfer page.
     */
    public function transfer(Request $request)
    {
        $domain   = $request->get('domain', '');
        $pricing  = $this->registrar->getTldPricing();
        return view('marketing.domain-transfer', compact('domain', 'pricing'));
    }

    /**
     * Handle transfer form submission — add domain transfer to cart.
     */
    public function transferSubmit(Request $request)
    {
        $request->validate([
            'domain'   => ['required', 'string', 'max:100', 'regex:/^[a-z0-9\-\.]+\.[a-z]{2,}$/i'],
            'epp_code' => ['required', 'string', 'min:6', 'max:64'],
        ]);

        $domainName = strtolower(trim($request->domain));

        // Look up transfer price from TLD pricing
        $pricing  = $this->registrar->getTldPricing();
        $price    = 0;
        $tlds     = array_keys($pricing);
        usort($tlds, fn($a, $b) => strlen($b) - strlen($a));
        foreach ($tlds as $tld) {
            if (str_ends_with($domainName, $tld)) {
                $price = (float) ($pricing[$tld]['transfer'] ?? $pricing[$tld]['register']);
                break;
            }
        }

        // Store EPP code in session temporarily (NOT in cart for security)
        session(['transfer_epp_' . md5($domainName) => $request->epp_code]);

        // Add to cart as a domain transfer item
        $cart = session('cart', []);
        $key  = 'transfer_' . preg_replace('/[^a-z0-9]/', '', $domainName);

        $cart[$key] = [
            'key'           => $key,
            'type'          => 'domain',
            'package_id'    => null,
            'name'          => 'Transfer: ' . $domainName,
            'billing_cycle' => 'yearly',
            'price'         => $price,
            'quantity'      => 1,
            'meta'          => [
                'domain'          => $domainName,
                'is_transfer'     => true,
                'epp_session_key' => md5($domainName),
            ],
        ];

        session(['cart' => $cart]);

        return redirect()->route('cart.index')
            ->with('success', "Domain transfer for {$domainName} added to cart. Complete payment to initiate the transfer.");
    }
}
