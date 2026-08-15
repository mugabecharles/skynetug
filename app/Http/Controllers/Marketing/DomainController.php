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

        // Clean the input — strip TLD if entered, get just the SLD
        $input = strtolower(trim($request->domain));

        // Remove common TLDs if the user typed a full domain
        $input = preg_replace('/\.(com|net|org|biz|info|ug|co\.ug|ac\.ug|or\.ug)$/i', '', $input);

        // Remove any non-valid characters
        $sld = preg_replace('/[^a-z0-9\-]/', '', $input);

        if (empty($sld) || strlen($sld) < 2) {
            return response()->json(['error' => 'Please enter a valid domain name.'], 422);
        }

        $results = $this->registrar->checkAvailability($sld);

        return response()->json($results);
    }
}
