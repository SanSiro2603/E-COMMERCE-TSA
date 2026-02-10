<?php
// app/Http/Controllers/SuperAdmin/AdminManagementController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        $admins = $query->withCount(['orders as orders_handled'])
            ->latest()
            ->paginate(10);

        return view('superadmin.admins.index', compact('admins'));
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

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin berhasil ditambahkan!');
    }

    public function show(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $admin->load(['orders' => function($q) {
            $q->latest()->limit(10);
        }]);

        $stats = [
            'total_orders' => $admin->orders()->count(),
            'completed_orders' => $admin->orders()->where('status', 'completed')->count(),
            'processing_orders' => $admin->orders()->where('status', 'processing')->count(),
            'total_revenue' => $admin->orders()->where('status', 'completed')->sum('grand_total'),
        ];

        return view('superadmin.admins.show', compact('admin', 'stats'));
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

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        if ($request->filled('password')) {
            $admin->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Data admin berhasil diperbarui!');
    }

    public function destroy(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $admin->delete();

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin berhasil dihapus!');
    }
}