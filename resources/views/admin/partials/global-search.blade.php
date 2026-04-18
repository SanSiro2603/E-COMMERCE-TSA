{{-- resources/views/admin/partials/global-search.blade.php --}}
{{-- Include ini di layouts/admin.blade.php tepat sebelum @stack('scripts') --}}
{{-- Dengan cara: @include('admin.partials.global-search') --}}

{{-- ===== MODAL OVERLAY ===== --}}
<div id="searchModal"
    class="fixed inset-0 z-[999] hidden"
    aria-modal="true" role="dialog">

    {{-- Backdrop --}}
    <div id="searchBackdrop"
        onclick="closeSearch()"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm">
    </div>

    {{-- Modal box --}}
    <div class="relative z-10 flex items-start justify-center pt-[12vh] px-4">
        <div id="searchBox"
            class="w-full max-w-xl bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-700 shadow-2xl overflow-hidden"
            style="animation: searchSlideIn .18s cubic-bezier(.4,0,.2,1)">

            {{-- Input --}}
            <div class="flex items-center gap-3 px-4 py-3.5 border-b border-gray-100 dark:border-zinc-800">
                <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-[22px] flex-shrink-0">search</span>
                <input
                    id="searchInput"
                    type="text"
                    placeholder="Cari pesanan, produk, pelanggan, kategori..."
                    class="flex-1 bg-transparent text-[14px] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 outline-none border-none focus:ring-0"
                    autocomplete="off"
                >
                <div id="searchSpinner" class="hidden">
                    <div class="w-4 h-4 border-2 border-soft-green border-t-transparent rounded-full animate-spin"></div>
                </div>
                <kbd class="hidden sm:inline-flex items-center gap-1 px-2 py-1 text-[10px] font-medium text-gray-400 dark:text-zinc-500 bg-gray-100 dark:bg-zinc-800 rounded-lg">
                    ESC
                </kbd>
            </div>

            {{-- Results --}}
            <div id="searchResults" class="max-h-[380px] overflow-y-auto custom-scrollbar py-2">
                {{-- Default state --}}
                <div id="searchEmpty" class="py-10 text-center">
                    <span class="material-symbols-outlined text-gray-300 dark:text-zinc-700 text-5xl">manage_search</span>
                    <p class="text-[13px] text-gray-400 dark:text-zinc-500 mt-2">Ketik untuk mulai mencari...</p>
                </div>
                {{-- Result list akan diisi JS --}}
                <div id="searchList" class="hidden px-2 space-y-0.5"></div>
                {{-- No result --}}
                <div id="searchNoResult" class="hidden py-10 text-center">
                    <span class="material-symbols-outlined text-gray-300 dark:text-zinc-700 text-5xl">search_off</span>
                    <p class="text-[13px] text-gray-400 dark:text-zinc-500 mt-2">Tidak ada hasil ditemukan</p>
                </div>
            </div>

            {{-- Footer hint --}}
            <div class="px-4 py-2.5 border-t border-gray-100 dark:border-zinc-800 flex items-center gap-4 text-[11px] text-gray-400 dark:text-zinc-600">
                <span class="flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-zinc-800 rounded text-[10px]">↑↓</kbd> navigasi
                </span>
                <span class="flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-zinc-800 rounded text-[10px]">↵</kbd> buka
                </span>
                <span class="flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-zinc-800 rounded text-[10px]">ESC</kbd> tutup
                </span>
                <span class="ml-auto">Cari: Pesanan · Produk · Pelanggan · Kategori</span>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes searchSlideIn {
        from { opacity: 0; transform: translateY(-12px) scale(.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .search-item { border-radius: 8px; }
    .search-item.focused { background: #f3f4f6; }
    .dark .search-item.focused { background: rgba(255,255,255,.05); }
</style>

<script>
// ── State ────────────────────────────────────────────────
let searchTimer   = null;
let focusedIndex  = -1;
let searchItems   = [];

// ── Buka / tutup ─────────────────────────────────────────
function openSearch() {
    document.getElementById('searchModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('searchInput').focus(), 50);
    focusedIndex = -1;
}

function closeSearch() {
    document.getElementById('searchModal').classList.add('hidden');
    document.body.style.overflow = '';
    document.getElementById('searchInput').value = '';
    resetResults();
}

function resetResults() {
    document.getElementById('searchEmpty').classList.remove('hidden');
    document.getElementById('searchList').classList.add('hidden');
    document.getElementById('searchNoResult').classList.add('hidden');
    document.getElementById('searchList').innerHTML = '';
    searchItems = [];
    focusedIndex = -1;
}

// ── Shortcut Ctrl+K / Cmd+K ──────────────────────────────
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const modal = document.getElementById('searchModal');
        modal.classList.contains('hidden') ? openSearch() : closeSearch();
    }
    if (e.key === 'Escape') closeSearch();
});

// ── Keyboard navigasi di dalam hasil ─────────────────────
document.getElementById('searchInput').addEventListener('keydown', function(e) {
    if (!searchItems.length) return;

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        focusedIndex = Math.min(focusedIndex + 1, searchItems.length - 1);
        updateFocus();
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        focusedIndex = Math.max(focusedIndex - 1, 0);
        updateFocus();
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (focusedIndex >= 0 && searchItems[focusedIndex]) {
            window.location.href = searchItems[focusedIndex].url;
        }
    }
});

function updateFocus() {
    document.querySelectorAll('.search-item').forEach((el, i) => {
        el.classList.toggle('focused', i === focusedIndex);
        if (i === focusedIndex) el.scrollIntoView({ block: 'nearest' });
    });
}

// ── Input → debounce → fetch ──────────────────────────────
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.trim();
    clearTimeout(searchTimer);

    if (q.length < 2) { resetResults(); return; }

    document.getElementById('searchSpinner').classList.remove('hidden');

    searchTimer = setTimeout(() => {
        fetch(`{{ route('admin.search') }}?q=${encodeURIComponent(q)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => renderResults(data))
        .catch(() => {})
        .finally(() => document.getElementById('searchSpinner').classList.add('hidden'));
    }, 280);
});

// ── Render hasil ──────────────────────────────────────────
const groupIcons = {
    'Pesanan'   : 'receipt_long',
    'Produk'    : 'inventory_2',
    'Pelanggan' : 'person',
    'Kategori'  : 'category',
};
const groupColors = {
    'Pesanan'   : 'text-blue-500',
    'Produk'    : 'text-soft-green',
    'Pelanggan' : 'text-purple-500',
    'Kategori'  : 'text-orange-500',
};

function renderResults(data) {
    const list = document.getElementById('searchList');
    list.innerHTML = '';
    searchItems = [];
    focusedIndex = -1;

    const total = Object.values(data).reduce((s, g) => s + g.length, 0);

    if (total === 0) {
        document.getElementById('searchEmpty').classList.add('hidden');
        document.getElementById('searchList').classList.add('hidden');
        document.getElementById('searchNoResult').classList.remove('hidden');
        return;
    }

    document.getElementById('searchEmpty').classList.add('hidden');
    document.getElementById('searchNoResult').classList.add('hidden');
    document.getElementById('searchList').classList.remove('hidden');

    Object.entries(data).forEach(([group, items]) => {
        if (!items.length) return;

        // Label grup
        const label = document.createElement('div');
        label.className = 'px-3 pt-3 pb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400 dark:text-zinc-600';
        label.textContent = group;
        list.appendChild(label);

        items.forEach(item => {
            const idx = searchItems.length;
            searchItems.push(item);

            const el = document.createElement('a');
            el.href = item.url;
            el.className = 'search-item flex items-center gap-3 px-3 py-2.5 cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-white/5';
            el.dataset.index = idx;
            el.innerHTML = `
                <span class="material-symbols-outlined text-[18px] flex-shrink-0 ${groupColors[group] || 'text-gray-400'}">
                    ${groupIcons[group] || 'search'}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-medium text-gray-900 dark:text-white truncate">${item.title}</p>
                    ${item.subtitle ? `<p class="text-[11px] text-gray-400 dark:text-zinc-500 truncate">${item.subtitle}</p>` : ''}
                </div>
                ${item.badge ? `<span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400 flex-shrink-0">${item.badge}</span>` : ''}
            `;
            el.addEventListener('mouseenter', () => {
                focusedIndex = idx;
                updateFocus();
            });
            list.appendChild(el);
        });
    });
}
</script>