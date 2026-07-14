<div class="overflow-x-auto mt-4">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-200 dark:border-zinc-800">
                    <tr>
                        <th class="px-4 py-4">
                            <input type="checkbox" id="check-all" class="bulk-checkbox rounded" title="Pilih semua">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Slug</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Produk</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors bg-white dark:bg-zinc-900" data-id="{{ $category->id }}">
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" class="bulk-checkbox row-checkbox rounded" value="{{ $category->id }}" onchange="onCheckboxChange()">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($category->children_count > 0)
                                        <button type="button" class="expand-btn" data-target="subtable-{{ $category->id }}" onclick="toggleSubRows(this)" title="Tampilkan/Sembunyikan sub-kategori">
                                            <span class="material-symbols-outlined expand-icon">chevron_right</span>
                                        </button>
                                    @else
                                        <div class="w-[22px] flex-shrink-0"></div>
                                    @endif
                                    @if($category->image)
                                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-9 h-9 object-cover rounded-lg shadow-sm flex-shrink-0">
                                    @else
                                        <div class="w-9 h-9 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-sm flex-shrink-0">
                                            {{ strtoupper(substr($category->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $category->name }}</p>
                                            @if($category->children_count > 0)
                                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded text-[10px] font-medium flex-shrink-0">
                                                    <span class="material-symbols-outlined text-xs">account_tree</span>
                                                    {{ $category->children_count }} sub
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-blue-500 dark:text-blue-400 mt-0.5">Kategori Utama</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs text-gray-600 dark:text-zinc-300 bg-gray-100 dark:bg-zinc-800 px-2 py-1 rounded break-all">{{ $category->slug }}</code>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 rounded-full text-xs font-semibold">
                                    <span class="material-symbols-outlined text-sm">inventory_2</span>{{ $category->products_count }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($category->is_active)
                                    <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full flex-shrink-0"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full flex-shrink-0"></span>Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 rounded-lg hover:bg-blue-100 transition-colors">
                                        <span class="material-symbols-outlined text-sm">edit</span>Edit
                                    </a>
                                    <button type="button"
                                            onclick="confirmDeleteCategory('{{ $category->name }}', '{{ route('admin.categories.destroy', $category) }}', {{ $category->children_count > 0 ? 'true' : 'false' }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 rounded-lg hover:bg-red-100 transition-colors">
                                        <span class="material-symbols-outlined text-sm">delete</span>Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        @if($category->children_count > 0)
                            <tr class="sub-wrapper" data-parent="{{ $category->id }}">
                                <td colspan="6" class="p-0 border-0">
                                    <table class="w-full sub-table" id="subtable-{{ $category->id }}">
                                        <tbody>
                                            @foreach($category->children as $sub)
                                                <tr data-id="{{ $sub->id }}" style="background-color: rgba(139,92,246,0.05); border-left: 3px solid rgba(139,92,246,0.35);">
                                                    <td class="px-4 py-3 text-center">
                                                        <input type="checkbox" class="bulk-checkbox row-checkbox rounded" value="{{ $sub->id }}" onchange="onCheckboxChange()">
                                                    </td>
                                                    <td class="py-3 px-6" style="padding-left: 6rem !important;">
                                                        <div class="flex items-center gap-3">
                                                            <div class="flex-shrink-0 w-[22px] flex items-center justify-center">
                                                                <svg width="14" height="24" viewBox="0 0 14 24" fill="none" class="text-purple-300 dark:text-purple-700">
                                                                    @if(!$loop->last)
                                                                        <line x1="2" y1="0" x2="2" y2="24" stroke="currentColor" stroke-width="1.5"/>
                                                                    @else
                                                                        <line x1="2" y1="0" x2="2" y2="12" stroke="currentColor" stroke-width="1.5"/>
                                                                    @endif
                                                                    <line x1="2" y1="12" x2="14" y2="12" stroke="currentColor" stroke-width="1.5"/>
                                                                </svg>
                                                            </div>
                                                            <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-sm flex-shrink-0">
                                                                {{ strtoupper(substr($sub->name, 0, 1)) }}
                                                            </div>
                                                            <div class="min-w-0">
                                                                <p class="text-sm font-medium text-gray-800 dark:text-zinc-200 truncate">{{ $sub->name }}</p>
                                                                <p class="text-xs text-purple-500 dark:text-purple-400 mt-0.5">Sub Kategori</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-3">
                                                        <code class="text-xs text-gray-500 dark:text-zinc-400 bg-gray-100 dark:bg-zinc-800 px-2 py-1 rounded break-all">{{ $sub->slug }}</code>
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1 bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 rounded-full text-xs font-semibold">
                                                            <span class="material-symbols-outlined text-sm">inventory_2</span>{{ $sub->products_count }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        @if($sub->is_active)
                                                            <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">
                                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full flex-shrink-0"></span>Aktif
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 rounded-full text-xs font-semibold">
                                                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full flex-shrink-0"></span>Nonaktif
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex items-center justify-center gap-2">
                                                            <a href="{{ route('admin.categories.edit', $sub) }}"
                                                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 rounded-lg hover:bg-blue-100 transition-colors">
                                                                <span class="material-symbols-outlined text-sm">edit</span>Edit
                                                            </a>
                                                            <button type="button"
                                                                    onclick="confirmDeleteCategory('{{ $sub->name }}', '{{ route('admin.categories.destroy', $sub) }}', false)"
                                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 rounded-lg hover:bg-red-100 transition-colors">
                                                                <span class="material-symbols-outlined text-sm">delete</span>Hapus
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endif

                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-4xl">category</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">Belum ada kategori</p>
                                    <p class="text-xs text-gray-500 dark:text-zinc-400 mb-4">Mulai dengan menambahkan kategori pertama</p>
                                    <a href="{{ route('admin.categories.create') }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-soft-green to-primary rounded-lg hover:shadow-lg transition-all">
                                        <span class="material-symbols-outlined text-lg">add</span>Tambah Kategori
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/30">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-600 dark:text-zinc-400">
                        Menampilkan <span class="font-semibold">{{ $categories->firstItem() }}</span>
                        sampai <span class="font-semibold">{{ $categories->lastItem() }}</span>
                        dari <span class="font-semibold">{{ $categories->total() }}</span> kategori utama
                    </div>
                    <div>{{ $categories->links() }}</div>
                </div>
            </div>
        @endif