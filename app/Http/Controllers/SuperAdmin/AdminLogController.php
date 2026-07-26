<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    /**
     * Tampilkan daftar log aktivitas Admin & Super Admin.
     */
    public function index(Request $request)
    {
        $query = AdminLog::query()->latest();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('admin_name', 'like', "%{$search}%")
                  ->orWhere('admin_email', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('device_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Action filter
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Summary Statistics
        $totalLogs = AdminLog::count();
        $todayLogins = AdminLog::where('action', 'Login')
            ->whereDate('created_at', now()->today())
            ->count();
        $uniqueDevices = AdminLog::whereNotNull('device_name')
            ->distinct('device_name')
            ->count('device_name');

        // Distinct actions for filter dropdown
        $actions = AdminLog::select('action')->distinct()->pluck('action');

        $logs = $query->paginate(15)->withQueryString();

        return view('superadmin.logs.index', compact(
            'logs',
            'totalLogs',
            'todayLogins',
            'uniqueDevices',
            'actions'
        ));
    }

    /**
     * Hapus log aktivitas lama (opsional).
     */
    public function destroy(AdminLog $log)
    {
        $log->delete();

        return back()->with('success', 'Data log aktivitas berhasil dihapus.');
    }
}
