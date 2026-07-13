<?php
// app/Http/Controllers/SuperAdmin/AdminManagementController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Helpers\LogHelper;
use App\Models\User;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'admin');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $admins = $query->latest()
            ->paginate(10);

        $logs = AdminLog::with('user')->latest()->paginate(15, ['*'], 'logs_page');

        return view('superadmin.admins.index', compact('admins', 'logs'));
    }

    public function create()
    {
        return view('superadmin.admins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        LogHelper::record('Tambah Admin', "Menambahkan akun admin baru: {$validated['email']}");

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin berhasil ditambahkan!');
    }

    public function show(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        return view('superadmin.admins.show', compact('admin'));
    }

    public function edit(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        return view('superadmin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $emailChanged = $admin->email !== $validated['email'];

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        if ($request->filled('password')) {
            $admin->update(['password' => Hash::make($validated['password'])]);
        }

        if ($emailChanged) {
            $admin->update(['google2fa_secret' => null]);
            LogHelper::record('Reset 2FA', "2FA otomatis di-reset untuk admin {$admin->email} karena email diubah");
        }

        LogHelper::record('Update Admin', "Memperbarui data admin: {$admin->email}");

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Data admin berhasil diperbarui!');
    }

    public function destroy(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $email = $admin->email;
        $admin->delete();

        LogHelper::record('Hapus Admin', "Menghapus akun admin: {$email}");

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin berhasil dihapus!');
    }

    public function toggleActive(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $admin->update(['is_active' => !$admin->is_active]);

        $status = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';
        LogHelper::record('Toggle Status', "Mengubah status akun admin {$admin->email} menjadi {$status}");
        
        return back()->with('success', "Akun admin berhasil {$status}.");
    }

    public function resetTwoFactor(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $admin->update(['google2fa_secret' => null]);

        LogHelper::record('Reset 2FA', "Mereset verifikasi 2FA untuk admin: {$admin->email}");

        return back()->with('success', '2FA untuk admin ini berhasil di-reset. Mereka akan diminta mengatur ulang pada saat login berikutnya.');
    }
    
    public function resetPassword(Request $request, User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin->update([
            'password' => Hash::make($validated['password'])
        ]);

        LogHelper::record('Reset Password', "Mereset password untuk admin: {$admin->email}");

        return back()->with('success', 'Password admin berhasil di-reset.');
    }
}