<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,approver,driver'],
            'approval_level' => ['required_if:role,approver', 'nullable', 'integer', 'min:1', 'max:2'],
        ]);

        try {
            $validated['password'] = Hash::make($validated['password']);
            
            User::create($validated);

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menambahkan user.');
        }
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'role' => ['required', 'in:admin,approver,driver'],
            'approval_level' => ['required_if:role,approver', 'nullable', 'integer', 'min:1', 'max:2'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        try {
            $user->update($validated);
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui user.');
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus user.');
        }
    }

    public function activate(User $user)
    {
        try {
            $user->restore();
            return back()->with('success', 'User berhasil diaktifkan.');
        } catch (\Exception $e) {
            Log::error('Error activating user: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengaktifkan user.');
        }
    }

    public function deactivate(User $user)
    {
        try {
            $user->delete();
            return back()->with('success', 'User berhasil dinonaktifkan.');
        } catch (\Exception $e) {
            Log::error('Error deactivating user: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menonaktifkan user.');
        }
    }
}