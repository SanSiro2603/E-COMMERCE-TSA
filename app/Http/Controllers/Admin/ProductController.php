<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $query = Product::with('category.parent');
    
    // Filter Search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%");
        });
    }
    
    // Filter Category
    if ($request->filled('category')) {
        $query->whereHas('category', function($q) use ($request) {
            $q->where('id', $request->category)
              ->orWhere('parent_id', $request->category);
        });
    }

    // Filter Status
    if ($request->filled('status')) {
        $query->where('is_active', $request->status === 'active' ? 1 : 0);
    }

    // Filter Stok
    if ($request->filled('stock_filter')) {
        if ($request->stock_filter === 'low') {
            $query->where('stock', '<=', 5)->where('stock', '>', 0);
        } elseif ($request->stock_filter === 'empty') {
            $query->where('stock', 0);
        }
    }

    // Filter Harga
    if ($request->filled('price_min')) {
        $query->where('price', '>=', (int) $request->price_min);
    }
    if ($request->filled('price_max')) {
        $query->where('price', '<=', (int) $request->price_max);
    }

    // Sort Kolom
    $sortable = ['name', 'price', 'stock', 'created_at'];
    $sortBy   = in_array($request->sort_by, $sortable) ? $request->sort_by : 'created_at';
    $sortDir  = $request->sort_dir === 'asc' ? 'asc' : 'desc';
    $query->orderBy($sortBy, $sortDir);

    $perPage  = in_array((int) $request->per_page, [10, 25, 50]) ? (int) $request->per_page : 10;
    $products = $query->paginate($perPage);
    $categories = Category::parentOnly()->where('is_active', true)->orderBy('name')->get();

    if ($request->ajax()) {
        // Query terpisah untuk stats
        $statsQuery = Product::with('category.parent');

        if ($request->filled('search')) {
            $search = $request->search;
            $statsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        if ($request->filled('category')) {
            $statsQuery->whereHas('category', function($q) use ($request) {
                $q->where('id', $request->category)
                  ->orWhere('parent_id', $request->category);
            });
        }
        if ($request->filled('status')) {
            $statsQuery->where('is_active', $request->status === 'active' ? 1 : 0);
        }
        if ($request->filled('stock_filter')) {
            if ($request->stock_filter === 'low') {
                $statsQuery->where('stock', '<=', 5)->where('stock', '>', 0);
            } elseif ($request->stock_filter === 'empty') {
                $statsQuery->where('stock', 0);
            }
        }
        if ($request->filled('price_min')) {
            $statsQuery->where('price', '>=', (int) $request->price_min);
        }
        if ($request->filled('price_max')) {
            $statsQuery->where('price', '<=', (int) $request->price_max);
        }

        $allProducts = $statsQuery->get();
        $stats = [
            'total'     => $allProducts->count(),
            'active'    => $allProducts->where('is_active', true)->count(),
            'low_stock' => $allProducts->where('stock', '<=', 5)->count(),
            'inactive'  => $allProducts->where('is_active', false)->count(),
        ];

        $appendKeys = ['search', 'category', 'status', 'stock_filter', 'price_min', 'price_max', 'sort_by', 'sort_dir', 'per_page'];
        $html       = $this->renderTableRows($products);
        $pagination = (string) $products->appends($request->only($appendKeys))->links();

        return response()->json([
            'html'       => $html,
            'pagination' => $pagination,
            'stats'      => $stats,
        ]);
    }

    return view('admin.products.index', compact('products', 'categories'));
}

   private function renderTableRows($products)
{
    if ($products->isEmpty()) {
        return '
            <tr>
                <td colspan="8" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl mb-3">inventory_2</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Tidak ada produk ditemukan</p>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Coba ubah filter atau kata kunci pencarian</p>
                    </div>
                </td>
            </tr>
        ';
    }

    $html = '';
    foreach ($products as $product) {

        // ── Gambar ──────────────────────────────────────────
        $imageHtml = $product->image
            ? '<img src="' . asset('storage/' . $product->image) . '" alt="' . e($product->name) . '" class="h-14 w-14 object-cover rounded-lg border border-gray-200 dark:border-zinc-700">'
            : '<div class="h-14 w-14 bg-gray-200 dark:bg-zinc-700 rounded-lg flex items-center justify-center border border-gray-300 dark:border-zinc-600">
                <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-xl">image</span>
               </div>';

        // ── Stok ─────────────────────────────────────────────
        $stockClass   = $product->stock <= 5
            ? 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400'
            : 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400';
        $stockWarning = $product->stock <= 5
            ? '<span class="material-symbols-outlined text-base">warning</span>'
            : '';

        // ── Status ───────────────────────────────────────────
        $statusClass = $product->is_active
            ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400'
            : 'bg-gray-100 dark:bg-gray-500/20 text-gray-700 dark:text-gray-400';
        $statusDot   = $product->is_active ? 'bg-green-500' : 'bg-gray-500';
        $statusText  = $product->is_active ? 'Aktif' : 'Nonaktif';

        // ── Deskripsi ────────────────────────────────────────
        $description = Str::limit($product->description, 50);

        // ── Kategori ─────────────────────────────────────────
        if ($product->category) {
            if ($product->category->parent) {
                $categoryHtml = '
                    <div class="flex flex-col gap-1">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-full w-fit">
                            <span class="material-symbols-outlined text-sm">category</span>
                            ' . e($product->category->parent->name) . '
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 text-xs font-medium rounded-full w-fit">
                            <span class="material-symbols-outlined text-sm">account_tree</span>
                            ' . e($product->category->name) . '
                        </span>
                    </div>';
            } else {
                $categoryHtml = '
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-full">
                        <span class="material-symbols-outlined text-sm">category</span>
                        ' . e($product->category->name) . '
                    </span>';
            }
        } else {
            $categoryHtml = '<span class="text-xs text-gray-400 dark:text-zinc-500">Tanpa Kategori</span>';
        }

        // ── Route ─────────────────────────────────────────────
        $editRoute    = route('admin.products.edit', $product);
        $deleteRoute  = route('admin.products.destroy', $product);
        $productName  = e($product->name);
        $csrfField    = csrf_field();
        $methodField  = method_field('DELETE');

        $html .= '
            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors product-row" data-product-id="' . $product->id . '">

                <!-- ✅ Checkbox -->
                <td class="px-4 py-4">
                    <input type="checkbox"
                           class="product-checkbox w-4 h-4 rounded border-gray-300 dark:border-zinc-600 text-soft-green focus:ring-soft-green cursor-pointer"
                           value="' . $product->id . '">
                </td>

                <!-- Gambar -->
                <td class="px-6 py-4">' . $imageHtml . '</td>

                <!-- Nama Produk -->
                <td class="px-6 py-4">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">' . $productName . '</p>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">' . e($description) . '</p>
                </td>

                <!-- Kategori -->
                <td class="px-6 py-4">' . $categoryHtml . '</td>

                <!-- Harga -->
                <td class="px-6 py-4">
                    <p class="text-sm font-bold text-gray-900 dark:text-white">Rp ' . number_format($product->price, 0, ',', '.') . '</p>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">per ' . e($product->unit ?? 'unit') . '</p>
                </td>

                <!-- Stok -->
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg font-semibold text-sm ' . $stockClass . '">
                        ' . $stockWarning . '
                        ' . $product->stock . '
                    </span>
                </td>

                <!-- Status -->
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full ' . $statusClass . '">
                        <span class="w-1.5 h-1.5 rounded-full ' . $statusDot . '"></span>
                        ' . $statusText . '
                    </span>
                </td>

                <!-- Aksi -->
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="' . $editRoute . '"
                           class="flex items-center gap-1 px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 rounded-lg text-xs font-medium transition-colors">
                            <span class="material-symbols-outlined text-base">edit</span>
                            Edit
                        </a>
                        <form action="' . $deleteRoute . '" method="POST" class="inline">
                            ' . $csrfField . '
                            ' . $methodField . '
                            <button type="button"
                                    onclick="confirmDelete(this.closest(\'form\'), \'' . $productName . '\')"
                                    class="flex items-center gap-1 px-3 py-1.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-lg text-xs font-medium transition-colors">
                                <span class="material-symbols-outlined text-base">delete</span>
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        ';
    }

    return $html;
}
    public function bulkDelete(Request $request)
{
    $request->validate([
        'ids'   => 'required|array',
        'ids.*' => 'exists:products,id',
    ]);

    $products = Product::whereIn('id', $request->ids)->get();

    foreach ($products as $product) {
        // Hapus gambar utama
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        // Hapus gallery
        if (!empty($product->images)) {
            foreach ($product->images as $img) {
                if (Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }
        }
        // Hapus sertifikat
        if ($product->health_certificate) {
            Storage::disk('public')->delete($product->health_certificate);
        }

        $product->delete();
    }

    return response()->json([
        'success' => true,
        'message' => count($request->ids) . ' produk berhasil dihapus.',
    ]);
}

public function bulkStatus(Request $request)
{
    $request->validate([
        'ids'    => 'required|array',
        'ids.*'  => 'exists:products,id',
        'status' => 'required|boolean',
    ]);

    Product::whereIn('id', $request->ids)->update(['is_active' => $request->status]);

    $label = $request->status ? 'diaktifkan' : 'dinonaktifkan';

    return response()->json([
        'success' => true,
        'message' => count($request->ids) . " produk berhasil {$label}.",
    ]);
}

    public function create()
    {
        $parentCategories = Category::parentOnly()
            ->where('is_active', true)
            ->with(['children' => fn($q) => $q->where('is_active', true)])
            ->orderBy('name')
            ->get();

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('parentCategories', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'        => 'required|exists:categories,id',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'stock'              => 'required|integer|min:0',
            'weight'             => 'nullable|numeric|min:0',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'gallery_images.*'   => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'health_certificate' => 'nullable|mimes:pdf|max:5120',
            'available_from'     => 'nullable|date',
            'is_active'          => 'required|boolean',
            'is_featured'        => 'nullable|boolean',
        ]);

        $data          = $request->all();
        $data['slug']  = Str::slug($request->name);
        $data['is_featured'] = $request->boolean('is_featured');

        $imagesPaths = [];

        if ($request->hasFile('image')) {
            $mainImage        = $request->file('image')->store('products', 'public');
            $data['image']    = $mainImage;
            $imagesPaths[]    = $mainImage;
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $imagesPaths[] = $image->store('products', 'public');
            }
        }

        $data['images'] = !empty($imagesPaths) ? $imagesPaths : null;

        if ($request->hasFile('health_certificate')) {
            $data['health_certificate'] = $request->file('health_certificate')->store('certificates', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $parentCategories = Category::parentOnly()
            ->where('is_active', true)
            ->with(['children' => fn($q) => $q->where('is_active', true)])
            ->orderBy('name')
            ->get();

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'parentCategories', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id'        => 'required|exists:categories,id',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'stock'              => 'required|integer|min:0',
            'weight'             => 'nullable|numeric|min:0',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'gallery_images.*'   => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'health_certificate' => 'nullable|mimes:pdf|max:5120',
            'available_from'     => 'nullable|date',
            'is_active'          => 'required|boolean',
            'is_featured'        => 'nullable|boolean',
            'remove_images'      => 'nullable|array',
        ]);

        $data                = $request->all();
        $data['slug']        = Str::slug($request->name);
        $data['is_featured'] = $request->boolean('is_featured');

        $existingImages = $product->images ?? [];

        if ($request->filled('remove_images')) {
            foreach ($request->remove_images as $imageToRemove) {
                if (Storage::disk('public')->exists($imageToRemove)) {
                    Storage::disk('public')->delete($imageToRemove);
                }
                $existingImages = array_values(array_filter($existingImages, fn($img) => $img !== $imageToRemove));
            }
        }

        if ($request->hasFile('image')) {
            if ($product->image && !in_array($product->image, $existingImages)) {
                Storage::disk('public')->delete($product->image);
            }
            $mainImage     = $request->file('image')->store('products', 'public');
            $data['image'] = $mainImage;
            if (!in_array($mainImage, $existingImages)) {
                array_unshift($existingImages, $mainImage);
            }
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $existingImages[] = $image->store('products', 'public');
            }
        }

        $data['images'] = !empty($existingImages) ? array_values($existingImages) : null;

        if ($request->hasFile('health_certificate')) {
            if ($product->health_certificate) {
                Storage::disk('public')->delete($product->health_certificate);
            }
            $data['health_certificate'] = $request->file('health_certificate')->store('certificates', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) Storage::disk('public')->delete($product->image);

        if (!empty($product->images)) {
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        if ($product->health_certificate) {
            Storage::disk('public')->delete($product->health_certificate);
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus!');
    }
}