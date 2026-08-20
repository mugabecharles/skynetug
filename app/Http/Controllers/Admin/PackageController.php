<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index()
    {
        $packages = HostingPackage::orderBy('type')->orderBy('sort_order')->paginate(20);
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        HostingPackage::create($data);
        return redirect()->route('admin.packages.index')->with('success', 'Package created.');
    }

    public function edit(HostingPackage $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, HostingPackage $package)
    {
        $data = $this->validated($request, $package->id);
        $package->update($data);
        return redirect()->route('admin.packages.index')->with('success', 'Package updated.');
    }

    public function destroy(HostingPackage $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name'                 => ['required', 'string', 'max:100'],
            'slug'                 => ['required', 'string', 'max:100', 'unique:hosting_packages,slug' . ($ignoreId ? ",$ignoreId" : '')],
            'type'                 => ['required', 'in:shared,wordpress,vps,email,ssl,backup,design'],
            'description'          => ['nullable', 'string'],
            'price_monthly'        => ['required', 'numeric', 'min:0'],
            'price_quarterly'      => ['nullable', 'numeric', 'min:0'],
            'price_semiannual'     => ['nullable', 'numeric', 'min:0'],
            'price_yearly'         => ['required', 'numeric', 'min:0'],
            'price_biennially'     => ['nullable', 'numeric', 'min:0'],
            'price_triennial'      => ['nullable', 'numeric', 'min:0'],
            'disk_space_mb'        => ['required', 'integer', 'min:0'],
            'email_accounts'       => ['nullable', 'integer', 'min:0'],
            'databases'            => ['nullable', 'integer', 'min:0'],
            'features_text'        => ['nullable', 'string'],
        ]);

        // Build features array from textarea
        $features = [];
        if (!empty($data['features_text'])) {
            $features = array_filter(array_map('trim', explode("\n", $data['features_text'])));
        }
        unset($data['features_text']);
        $data['features']              = array_values($features);
        $data['is_active']             = $request->boolean('is_active', true);
        $data['is_featured']           = $request->boolean('is_featured');
        $data['ssl_included']          = $request->boolean('ssl_included');
        $data['softaculous_included']  = $request->boolean('softaculous_included');
        $data['backup_included']       = $request->boolean('backup_included');
        $data['price_quarterly']       = $data['price_quarterly'] ?? 0;
        $data['price_semiannual']      = $data['price_semiannual'] ?? 0;
        $data['price_biennially']      = $data['price_biennially'] ?? 0;
        $data['price_triennial']       = $data['price_triennial'] ?? 0;
        $data['email_accounts']        = $data['email_accounts'] ?? 0;
        $data['databases']             = $data['databases'] ?? 0;
        $data['bandwidth_mb']          = 0;

        return $data;
    }
}
