<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $sort = $request->get('sort', 'latest');
        $status = $request->get('status', '');
        $type = $request->get('type', '');

        $categories = Category::parentOnly()
            ->with([
                'children' => function ($q) use ($status) {
                    $q->withCount('products');
                    if ($status !== '') {
                        $q->where('is_active', $status);
                    }
                }
            ])
            ->withCount(['products', 'children'])
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->when($status !== '', fn($q) => $q->where('is_active', $status))
            ->when($type === 'parent', fn($q) => $q->has('children'))
            ->when($sort === 'name_asc', fn($q) => $q->orderBy('name', 'asc'))
            ->when($sort === 'name_desc', fn($q) => $q->orderBy('name', 'desc'))
            ->when($sort === 'products', fn($q) => $q->orderByDesc('products_count'))
            ->when($sort === 'latest', fn($q) => $q->latest())
            ->paginate(10)
            ->withQueryString();

        $totalParent = Category::parentOnly()->count();
        $totalSub = Category::childOnly()->count();

        return view('admin.categories.index', compact('categories', 'totalParent', 'totalSub'));
    }

    public function create()
    {
        $parentCategories = Category::parentOnly()->where('is_active', true)->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function checkName(Request $request)
    {
        $name = $request->query('name');
        $excludeId = $request->query('exclude_id');

        if (!$name) {
            return response()->json(['available' => true]);
        }

        $query = Category::where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $exists = $query->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Nama ini sudah digunakan.' : 'Nama tersedia.',
        ]);
    }

    public function store(Request $request)
    {
        // ── Kategori Utama ──
        if ($request->has('single_sub') === false && is_null($request->parent_id) && !$request->has('sub_names')) {
            $request->validate([
                'name' => 'required|string|max:100|unique:categories',
                'slug' => 'required|string|max:120|unique:categories',
                'is_active' => 'required|boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
            }

            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->slug),
                'is_active' => $request->boolean('is_active'),
                'image' => $imagePath,
                'parent_id' => null,
            ]);

            // FIX [konflik 1]: gabung LogHelper (stash) + redirect ke create dengan
            // flash last_parent_id (upstream) agar flow tambah sub-kategori tetap jalan
            LogHelper::record('Tambah Kategori', "Menambahkan kategori baru: {$request->name}");

            return redirect()->route('admin.categories.create')
                ->with('success_parent', 'Kategori utama "' . $category->name . '" berhasil disimpan! Sekarang tambahkan sub kategorinya.')
                ->with('last_parent_id', $category->id);
        }

        // ── Multiple Sub Kategori ──
        $request->validate([
            'parent_id' => 'required|exists:categories,id',
            'sub_names' => 'required|array|min:1',
            'sub_names.*' => 'required|string|max:100|distinct',
            'sub_slugs' => 'required|array|min:1',
            'sub_slugs.*' => 'required|string|max:120|distinct',
        ]);

        $saved = 0;
        $skipped = [];

        foreach ($request->sub_names as $i => $subName) {
            $subSlug = Str::slug($request->sub_slugs[$i] ?? $subName);

            if (Category::where('name', $subName)->exists()) {
                $skipped[] = $subName;
                continue;
            }
            if (Category::where('slug', $subSlug)->exists()) {
                $subSlug = $subSlug . '-' . uniqid();
            }

            Category::create([
                'name' => $subName,
                'slug' => $subSlug,
                'is_active' => true,
                'parent_id' => $request->parent_id,
            ]);

            $saved++;
        }

        $message = $saved . ' sub kategori berhasil ditambahkan!';
        if (!empty($skipped)) {
            $message .= ' ' . count($skipped) . ' dilewati karena nama sudah ada: ' . implode(', ', $skipped) . '.';
        }

        if ($saved > 0) {
            $parentName = Category::find($request->parent_id)?->name ?? '-';
            LogHelper::record('Tambah Sub Kategori', "Menambahkan {$saved} sub kategori baru di bawah kategori: {$parentName}");
        }

        return redirect()->route('admin.categories.index')
            ->with('success', $message);
    }

    public function edit(Category $category)
    {
        $category->load(['children' => fn($q) => $q->withCount('products')]);

        $parentCategories = Category::parentOnly()
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'slug' => 'required|string|max:120|unique:categories,slug,' . $category->id,
            'is_active' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // FIX [konflik 2]: ambil proteksi validasi dari upstream — kategori yang
        // sudah punya anak tidak boleh dijadikan sub-kategori.
        // Posisi pengecekan: sebelum proses gambar agar tidak waste I/O jika gagal.
        if ($request->parent_id && $category->children()->exists()) {
            return back()->withInput()
                ->with('error', 'Kategori yang memiliki sub-kategori tidak bisa dijadikan sub-kategori.');
        }

        $imagePath = $category->image;

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        if ($request->boolean('remove_image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $imagePath = null;
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'is_active' => $request->boolean('is_active'),
            'image' => $imagePath,
            'parent_id' => $request->parent_id ?? null,
        ]);

        // FIX [konflik 2 lanjutan]: tambah LogHelper dari stash
        LogHelper::record('Update Kategori', "Memperbarui kategori: {$request->name}");

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Tidak bisa dihapus! Kategori ini masih digunakan oleh produk.');
        }

        if ($category->children()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Tidak bisa dihapus! Hapus sub-kategori terlebih dahulu.');
        }

        // FIX [konflik 3]: gabung hapus gambar (upstream) + LogHelper (stash)
        // Simpan nama sebelum delete agar bisa dipakai di LogHelper
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $name = $category->name;
        $category->delete();

        LogHelper::record('Hapus Kategori', "Menghapus kategori: {$name}");

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    public function bulkAction(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:categories,id'],
        ]);

        $ids = $request->input('ids');
        $action = $request->input('action');

        switch ($action) {

            case 'activate':
                Category::whereIn('id', $ids)->update(['is_active' => true]);
                $message = count($ids) . ' kategori berhasil diaktifkan.';
                LogHelper::record('Aktifkan Kategori', "Mengaktifkan " . count($ids) . " kategori/sub kategori sekaligus (ID: " . implode(', ', $ids) . ')');
                break;

            case 'deactivate':
                Category::whereIn('id', $ids)->update(['is_active' => false]);
                $message = count($ids) . ' kategori berhasil dinonaktifkan.';
                LogHelper::record('Nonaktifkan Kategori', "Menonaktifkan " . count($ids) . " kategori/sub kategori sekaligus (ID: " . implode(', ', $ids) . ')');
                break;

            case 'delete':
                $deleted = 0;
                $skipped = 0;

                foreach ($ids as $id) {
                    $category = Category::withCount('children')->find($id);
                    if (!$category)
                        continue;

                    if ($category->children_count > 0) {
                        $skipped++;
                        continue;
                    }

                    if ($category->image && Storage::disk('public')->exists($category->image)) {
                        Storage::disk('public')->delete($category->image);
                    }

                    $category->delete();
                    $deleted++;
                }

                if ($skipped > 0) {
                    $message = "{$deleted} kategori dihapus. {$skipped} dilewati (masih punya sub-kategori).";
                } else {
                    $message = "{$deleted} kategori berhasil dihapus.";
                }

                if ($deleted > 0) {
                    LogHelper::record('Hapus Kategori (Bulk)', "Menghapus {$deleted} kategori/sub kategori sekaligus.");
                }
                break;

            default:
                $message = 'Aksi tidak dikenali.';
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', $message);
    }
}