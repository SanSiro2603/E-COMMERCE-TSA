<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $parentCategories = Category::parentOnly()
            ->with('children')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->latest()
            ->paginate(10, ['*'], 'parent_page');

        $subCategories = Category::childOnly()
            ->with('parent')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->latest()
            ->paginate(10, ['*'], 'sub_page');

        return view('admin.categories.index', compact('parentCategories', 'subCategories'));
    }

    public function create()
    {
        $parentCategories = Category::parentOnly()->where('is_active', true)->get();
    return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name'        => 'required|string|max:100|unique:categories',
        'description' => 'nullable|string',
        'is_active'   => 'required|boolean',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'parent_id'   => 'nullable|exists:categories,id',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('categories', 'public');
    }

    $category = Category::create([
        'name'        => $request->name,
        'slug'        => Str::slug($request->name),
        'description' => $request->description,
        'is_active'   => $request->boolean('is_active'),
        'image'       => $imagePath,
        'parent_id'   => $request->parent_id ?? null,
    ]);

    // Kalau kategori utama → kembali ke halaman create
    // Kalau sub kategori → redirect ke index
    if (is_null($category->parent_id)) {
    return redirect()->route('admin.categories.create')
        ->with('success_parent', 'Kategori utama "' . $category->name . '" berhasil disimpan! Sekarang tambahkan sub kategorinya.')
        ->with('last_parent_id', $category->id);
}

    return redirect()->route('admin.categories.index')
        ->with('success', 'Sub kategori berhasil ditambahkan!');
}
    public function edit(Category $category)
    {
        $parentCategories = Category::parentOnly()
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active'   => 'required|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'parent_id'   => 'nullable|exists:categories,id',
        ]);

        if ($request->parent_id && $category->children()->exists()) {
            return back()->withInput()
                ->with('error', 'Kategori yang memiliki sub-kategori tidak bisa dijadikan sub-kategori.');
        }

        $imagePath = $category->image;

        if ($request->hasFile('image')) {
            if ($category->image) Storage::disk('public')->delete($category->image);
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        if ($request->boolean('remove_image')) {
            if ($category->image) Storage::disk('public')->delete($category->image);
            $imagePath = null;
        }

        $category->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active'),
            'image'       => $imagePath,
            'parent_id'   => $request->parent_id ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus! Kategori ini masih digunakan oleh produk.');
        }

        if ($category->children()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus! Hapus sub-kategori terlebih dahulu.');
        }

        if ($category->image) Storage::disk('public')->delete($category->image);

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}