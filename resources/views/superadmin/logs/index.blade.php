@extends('layouts.superadmin')

@section('page-title', 'Log Aktivitas Sistem')
@section('page-subtitle', 'Pantau aktivitas login, logout, IP address, dan detail perangkat HP/PC pengelola')

@section('content')
<style>
    /* Custom Glassmorphism & Gradient Effects */
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(229, 231, 235, 0.8);
    }
    .dark .glass-card {
        background: rgba(18, 18, 20, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .badge-device-xiaomi {
        background: rgba(255, 105, 0, 0.12);
        color: #ff6900;
        border: 1px solid rgba(255, 105, 0, 0.3);
    }
    .badge-device-samsung {
        background: rgba(20, 110, 245, 0.12);
        color: #2563eb;
        border: 1px solid rgba(37, 99, 235, 0.3);
    }
    .dark .badge-device-samsung {
        color: #60a5fa;
    }
    .badge-device-apple {
        background: rgba(148, 163, 184, 0.12);
        color: #475569;
        border: 1px solid rgba(148, 163, 184, 0.3);
    }
    .dark .badge-device-apple {
        color: #cbd5e1;
    }
    .badge-device-pc {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .dark .badge-device-pc {
        color: #34d399;
    }
</style>

<div class="space-y-6">

    <!-- Header & Page Summary -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">history</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white font-display">Log Aktivitas Admin</h2>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Monitoring aktivitas real-time pengelola sistem & deteksi perangkat</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Information Pill -->
            <div class="inline-flex items-center gap-2 rounded-xl bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 px-3.5 py-2 text-xs font-medium text-gray-600 dark:text-zinc-400 shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Live Monitoring Active</span>
            </div>
        </div>
    </div>

    <!-- Stat Cards Section -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <!-- Total Logs Card -->
        <div class="glass-card relative overflow-hidden rounded-2xl p-5 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10 blur-2xl"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-zinc-400">Total Rekaman Log</span>
                    <h3 class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white font-display tracking-tight">{{ number_format($totalLogs) }}</h3>
                    <p class="mt-1 text-[11px] text-gray-500 dark:text-zinc-500">Aktivitas admin terdata di database</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/25">
                    <span class="material-symbols-outlined text-[28px]">database</span>
                </div>
            </div>
        </div>

        <!-- Today Logins Card -->
        <div class="glass-card relative overflow-hidden rounded-2xl p-5 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10 blur-2xl"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-zinc-400">Sesi Login Hari Ini</span>
                    <h3 class="mt-2 text-3xl font-extrabold text-blue-600 dark:text-blue-400 font-display tracking-tight">{{ number_format($todayLogins) }}</h3>
                    <p class="mt-1 text-[11px] text-gray-500 dark:text-zinc-500">Pengelola berhasil login hari ini</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/25">
                    <span class="material-symbols-outlined text-[28px]">login</span>
                </div>
            </div>
        </div>

        <!-- Unique Devices Card -->
        <div class="glass-card relative overflow-hidden rounded-2xl p-5 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-500/10 blur-2xl"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-zinc-400">Perangkat Terdeteksi</span>
                    <h3 class="mt-2 text-3xl font-extrabold text-amber-600 dark:text-amber-400 font-display tracking-tight">{{ number_format($uniqueDevices) }}</h3>
                    <p class="mt-1 text-[11px] text-gray-500 dark:text-zinc-500">Tipe HP & Komputer unik</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/25">
                    <span class="material-symbols-outlined text-[28px]">devices</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="glass-card rounded-2xl p-5 shadow-sm">
        <form method="GET" action="{{ route('superadmin.logs.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                
                <!-- Search Keyword -->
                <div class="relative lg:col-span-2">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-zinc-400 mb-1.5">Cari Data</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-[20px]">search</span>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Nama admin, email, Xiaomi, Samsung, IP..." 
                               class="w-full rounded-xl border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 pl-10 pr-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition duration-200">
                    </div>
                </div>

                <!-- Action Filter -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-zinc-400 mb-1.5">Jenis Aksi</label>
                    <select name="action" class="w-full rounded-xl border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition duration-200">
                        <option value="">Semua Jenis Aksi</option>
                        @foreach($actions as $act)
                            <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $act }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-zinc-400 mb-1.5">Tanggal</label>
                    <input type="date" 
                           name="date" 
                           value="{{ request('date') }}"
                           class="w-full rounded-xl border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition duration-200">
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-center justify-end gap-2 border-t border-gray-100 dark:border-zinc-800/80 pt-3.5">
                @if(request('search') || request('action') || request('date'))
                    <a href="{{ route('superadmin.logs.index') }}" 
                       class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-100 dark:bg-zinc-800 px-4 py-2 text-xs font-semibold text-gray-700 dark:text-zinc-300 transition hover:bg-gray-200 dark:hover:bg-zinc-700">
                        <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                        Reset Filter
                    </a>
                @endif

                <button type="submit" 
                        class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-2 text-xs font-bold text-white shadow-md shadow-emerald-500/20 transition hover:from-emerald-500 hover:to-teal-500 hover:scale-[1.01] focus:outline-none">
                    <span class="material-symbols-outlined text-[16px]">filter_list</span>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="glass-card overflow-hidden rounded-2xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-zinc-800 bg-gray-50/80 dark:bg-zinc-800/50 text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-zinc-400">
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Pengelola (Admin)</th>
                        <th class="px-6 py-4">Aksi</th>
                        <th class="px-6 py-4">Perangkat & IP</th>
                        <th class="px-6 py-4">Detail Deskripsi</th>
                        <th class="px-4 py-4 text-center">User Agent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800/60 text-xs">
                    @forelse($logs as $log)
                        <tr class="transition duration-150 hover:bg-gray-50/80 dark:hover:bg-zinc-800/40">
                            
                            <!-- Timestamp -->
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $log->created_at->format('d M Y, H:i') }}</div>
                                <div class="mt-0.5 text-[11px] font-medium text-gray-500 dark:text-zinc-400">{{ $log->created_at->diffForHumans() }}</div>
                            </td>

                            <!-- User Info -->
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 font-bold text-white shadow-sm">
                                        {{ strtoupper(substr($log->admin_name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $log->admin_name ?? 'Sistem / Anonim' }}</div>
                                        <div class="text-[11px] text-gray-500 dark:text-zinc-400">{{ $log->admin_email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Action Badge -->
                            <td class="whitespace-nowrap px-6 py-4">
                                @php
                                    $actionLower = strtolower($log->action);
                                    $badgeStyle = match(true) {
                                        str_contains($actionLower, 'login') => 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-500/30',
                                        str_contains($actionLower, 'logout') => 'bg-slate-500/10 text-slate-700 dark:text-slate-400 border-slate-500/30',
                                        str_contains($actionLower, '2fa') => 'bg-blue-500/10 text-blue-700 dark:text-blue-400 border-blue-500/30',
                                        str_contains($actionLower, 'hapus') => 'bg-rose-500/10 text-rose-700 dark:text-rose-400 border-rose-500/30',
                                        default => 'bg-purple-500/10 text-purple-700 dark:text-purple-400 border-purple-500/30'
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 rounded-lg border px-3 py-1 text-xs font-bold shadow-2xs {{ $badgeStyle }}">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <!-- Device & IP -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    @php
                                        $deviceName = $log->device_name ?? 'Desktop / Unknown';
                                        $deviceClass = match(true) {
                                            str_contains(strtolower($deviceName), 'xiaomi') || str_contains(strtolower($deviceName), 'poco') || str_contains(strtolower($deviceName), 'redmi') => 'badge-device-xiaomi',
                                            str_contains(strtolower($deviceName), 'samsung') => 'badge-device-samsung',
                                            str_contains(strtolower($deviceName), 'iphone') || str_contains(strtolower($deviceName), 'mac') || str_contains(strtolower($deviceName), 'ipad') => 'badge-device-apple',
                                            default => 'badge-device-pc'
                                        };
                                    @endphp
                                    
                                    <!-- Device Badge -->
                                    <div class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-bold {{ $deviceClass }}">
                                        <span class="material-symbols-outlined text-[15px]">
                                            {{ $log->device_type === 'Mobile' ? 'smartphone' : ($log->device_type === 'Tablet' ? 'tablet' : 'desktop_windows') }}
                                        </span>
                                        <span>{{ $deviceName }}</span>
                                    </div>
                                    
                                    <!-- OS, Browser, IP -->
                                    <div class="flex items-center gap-2 text-[11px] font-medium text-gray-500 dark:text-zinc-400">
                                        <span>{{ $log->operating_system ?? '-' }}</span>
                                        <span>•</span>
                                        <span>{{ $log->browser ?? '-' }}</span>
                                        <span class="rounded-md bg-gray-100 dark:bg-zinc-800 px-1.5 py-0.5 font-mono text-[10px] text-gray-600 dark:text-zinc-400 border border-gray-200 dark:border-zinc-700">
                                            {{ $log->ip_address ?? '127.0.0.1' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Description -->
                            <td class="px-6 py-4">
                                <p class="text-xs text-gray-700 dark:text-zinc-300 font-medium leading-relaxed max-w-sm">
                                    {{ $log->description }}
                                </p>
                            </td>

                            <!-- Raw User Agent Modal Trigger -->
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                <button type="button" 
                                        onclick="showAgentModal('{{ addslashes($log->admin_name) }}', '{{ addslashes($log->device_name) }}', '{{ addslashes($log->user_agent) }}')"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400 transition hover:bg-emerald-500 hover:text-white dark:hover:bg-emerald-500 dark:hover:text-white"
                                        title="Lihat Raw User Agent">
                                    <span class="material-symbols-outlined text-[18px]">info</span>
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-zinc-800 text-gray-400 dark:text-zinc-500 border border-gray-200 dark:border-zinc-700">
                                    <span class="material-symbols-outlined text-[36px]">search_off</span>
                                </div>
                                <h4 class="mt-4 text-base font-bold text-gray-900 dark:text-white">Tidak Ada Data Log Aktivitas</h4>
                                <p class="mt-1 text-xs text-gray-500 dark:text-zinc-400">Tidak ada log aktivitas yang cocok dengan kriteria filter pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="border-t border-gray-100 dark:border-zinc-800 bg-gray-50/50 dark:bg-zinc-800/30 px-6 py-4">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>

<!-- User Agent Detail Modal -->
<div id="agentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
    <div class="glass-card w-full max-w-lg rounded-2xl p-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-zinc-800 pb-4">
            <div class="flex items-center gap-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                    <span class="material-symbols-outlined text-[20px]">devices</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white" id="modalAdminName">Detail Perangkat</h3>
                    <p class="text-xs text-gray-500 dark:text-zinc-400" id="modalDeviceName">Informasi User Agent Mentah</p>
                </div>
            </div>
            <button type="button" onclick="closeAgentModal()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-zinc-800 dark:hover:text-white">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <div class="mt-4 space-y-3">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-zinc-400 mb-1">HTTP User-Agent Header</label>
                <div class="rounded-xl border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 p-3.5 font-mono text-xs text-gray-800 dark:text-zinc-300 leading-relaxed break-words shadow-inner" id="modalUserAgent">
                    -
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button" onclick="closeAgentModal()" class="rounded-xl bg-gray-200 dark:bg-zinc-800 px-5 py-2 text-xs font-bold text-gray-800 dark:text-zinc-200 hover:bg-gray-300 dark:hover:bg-zinc-700 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function showAgentModal(adminName, deviceName, userAgent) {
        document.getElementById('modalAdminName').innerText = adminName || 'Admin';
        document.getElementById('modalDeviceName').innerText = deviceName || 'Detail Perangkat';
        document.getElementById('modalUserAgent').innerText = userAgent || 'User Agent tidak tersedia';
        document.getElementById('agentModal').classList.remove('hidden');
        document.getElementById('agentModal').classList.add('flex');
    }

    function closeAgentModal() {
        document.getElementById('agentModal').classList.add('hidden');
        document.getElementById('agentModal').classList.remove('flex');
    }
</script>
@endsection
