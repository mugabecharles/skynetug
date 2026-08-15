<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:191', 'unique:users'],
            'password' => ['required', Password::min(8)],
            'role'     => ['required', 'in:customer,super_admin,billing_manager,technical_admin,support_agent,sales_manager'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'country'  => ['nullable', 'string', 'size:2'],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:191', 'unique:users,email,' . $user->id],
            'password'  => ['nullable', Password::min(8)],
            'role'      => ['required', 'in:customer,super_admin,billing_manager,technical_admin,support_agent,sales_manager'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->has('is_active');

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
