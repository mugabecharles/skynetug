<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::latest()->paginate(20);
        return view('admin.servers.index', compact('servers'));
    }

    public function create()
    {
        return view('admin.servers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'hostname'      => ['required', 'string', 'max:255'],
            'ip_address'    => ['required', 'string', 'max:45'],
            'type'          => ['required', 'in:shared,vps,dedicated'],
            'username'      => ['nullable', 'string', 'max:100'],
            'api_hash'      => ['nullable', 'string'],
            'max_accounts'  => ['nullable', 'integer', 'min:1'],
            'ns1'           => ['nullable', 'string', 'max:255'],
            'ns2'           => ['nullable', 'string', 'max:255'],
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        Server::create($data);
        return redirect()->route('admin.servers.index')->with('success', 'Server added.');
    }

    public function edit(Server $server)
    {
        return view('admin.servers.edit', compact('server'));
    }

    public function update(Request $request, Server $server)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'hostname'     => ['required', 'string', 'max:255'],
            'ip_address'   => ['required', 'string', 'max:45'],
            'type'         => ['required', 'in:shared,vps,dedicated'],
            'username'     => ['nullable', 'string'],
            'api_hash'     => ['nullable', 'string'],
            'max_accounts' => ['nullable', 'integer'],
            'ns1'          => ['nullable', 'string'],
            'ns2'          => ['nullable', 'string'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $server->update($data);
        return redirect()->route('admin.servers.index')->with('success', 'Server updated.');
    }

    public function destroy(Server $server)
    {
        $server->delete();
        return redirect()->route('admin.servers.index')->with('success', 'Server removed.');
    }
}
