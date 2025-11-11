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
        $query = Product::with('category');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Get paginated products
        $products = $query->latest()->paginate(10);
        
        // Get all categories for filter dropdown
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        // If AJAX request, return JSON with HTML
        if ($request->ajax()) {
            // Get all filtered products for accurate stats
            $allFilteredProducts = Product::with('category');
            
            if ($request->filled('search')) {
                $search = $request->search;
                $allFilteredProducts->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
                });
            }
            
            if ($request->filled('category')) {
                $allFilteredProducts->where('category_id', $request->category);
            }
            
            $allProducts = $allFilteredProducts->get();
            
            // Calculate stats
            $stats = [
                'total' => $allProducts->count(),
                'active' => $allProducts->where('is_active', true)->count(),
                'low_stock' => $allProducts->where('stock', '<=', 5)->count(),
                'inactive' => $allProducts->where('is_active', false)->count(),
            ];
            
            // Render table rows HTML
            $html = $this->renderTableRows($products);
            
            // Render pagination HTML
            $pagination = $products->appends($request->only(['search', 'category']))->links()->render();
            
            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'stats' => $stats
            ]);
        }
        
        // Regular page load
        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Render table rows HTML for AJAX response
     */
    private function renderTableRows($products)
    {
        if ($products->isEmpty()) {
            return '
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
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
            // Image HTML
            $imageHtml = $product->image 
                ? '<img src="' . asset('storage/' . $product->image) . '" alt="' . e($product->name) . '" class="h-14 w-14 object-cover rounded-lg border border-gray-200 dark:border-zinc-700">'
                : '<div class="h-14 w-14 bg-gray-200 dark:bg-zinc-700 rounded-lg flex items-center justify-center border border-gray-300 dark:border-zinc-600">
                    <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-xl">image</span>
                   </div>';
            
            // Stock styling
            $stockClass = $product->stock <= 5 
                ? 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400' 
                : 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400';
            
            $stockWarning = $product->stock <= 5 
                ? '<span class="material-symbols-outlined text-base">warning</span>' 
                : '';
            
            // Status styling
            $statusClass = $product->is_active 
                ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400' 
                : 'bg-gray-100 dark:bg-gray-500/20 text-gray-700 dark:text-gray-400';
            
            $statusDot = $product->is_active ? 'bg-green-500' : 'bg-gray-500';
            $statusText = $product->is_active ? 'Aktif' : 'Nonaktif';
            
            // Category name
            $categoryName = $product->category ? e($product->category->name) : 'Tanpa Kategori';
            
            // Description
            $description = \Str::limit($product->description, 50);
            
            $html .= '
                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors" data-product-id="' . $product->id . '">
                    <td class="px-6 py-4">
                        ' . $imageHtml . '
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">' . e($product->name) . '</p>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">' . e($description) . '</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 text-xs font-medium rounded-full">
                            <span class="material-symbols-outlined text-sm">category</span>
                            ' . $categoryName . '
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">Rp ' . number_format($product->price, 0, ',', '.') . '</p>
                        <p class="text-xs text-gray-500 dark:text-zinc-400">per ' . e($product->unit ?? 'unit') . '</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg font-semibold text-sm ' . $stockClass . '">
                            ' . $stockWarning . '
                            ' . $product->stock . '
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full ' . $statusClass . '">
                            <span class="w-1.5 h-1.5 rounded-full ' . $statusDot . '"></span>
                            ' . $statusText . '
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="' . route('admin.products.edit', $product) . '" 
                               class="flex items-center gap-1 px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 rounded-lg text-xs font-medium transition-colors">
                                <span class="material-symbols-outlined text-base">edit</span>
                                Edit
                            </a>
                            <form action="' . route('admin.products.destroy', $product) . '" method="POST" class="inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" 
                                        onclick="return confirm(\'Yakin ingin menghapus produk ' . e($product->name) . '?\')" 
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

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Multiple images
            'health_certificate' => 'nullable|mimes:pdf|max:5120',
            'available_from' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle multiple images untuk gallery
        $imagesPaths = [];
        
        // Upload gambar utama (main image)
        if ($request->hasFile('image')) {
            $mainImage = $request->file('image')->store('products', 'public');
            $data['image'] = $mainImage;
            $imagesPaths[] = $mainImage; // Tambahkan ke array images
        }

        // Upload gambar tambahan (gallery images)
       if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $image) {
            $path = $image->store('products', 'public');
            $imagesPaths[] = $path; // Tambahkan ke array
        }
    }

        // Simpan semua path gambar ke kolom images (JSON)
        $data['images'] = !empty($imagesPaths) ? $imagesPaths : null;

        // Upload sertifikat
        if ($request->hasFile('health_certificate')) {
            $data['health_certificate'] = $request->file('health_certificate')->store('certificates', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Multiple images
            'health_certificate' => 'nullable|mimes:pdf|max:5120',
            'available_from' => 'nullable|date',
            'is_active' => 'required|boolean',
            'remove_images' => 'nullable|array', // Array of images to remove
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Get existing images
        $existingImages = $product->images ?? [];
        
        // Remove selected images
        if ($request->filled('remove_images')) {
            foreach ($request->remove_images as $imageToRemove) {
                // Delete from storage
                if (Storage::disk('public')->exists($imageToRemove)) {
                    Storage::disk('public')->delete($imageToRemove);
                }
                // Remove from array
                $existingImages = array_values(array_filter($existingImages, function($img) use ($imageToRemove) {
                    return $img !== $imageToRemove;
                }));
            }
        }

        // Update main image if uploaded
        if ($request->hasFile('image')) {
            // Delete old main image if exists and not in existing images array
            if ($product->image && !in_array($product->image, $existingImages)) {
                Storage::disk('public')->delete($product->image);
            }
            
            $mainImage = $request->file('image')->store('products', 'public');
            $data['image'] = $mainImage;
            
            // Add to existing images if not already there
            if (!in_array($mainImage, $existingImages)) {
                array_unshift($existingImages, $mainImage);
            }
        }

        // Upload new gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('products', 'public');
                $existingImages[] = $path;
            }
        }

        // Update images array
        $data['images'] = !empty($existingImages) ? array_values($existingImages) : null;

        // Update health certificate
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
        // Delete main image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        // Delete all gallery images
        if (!empty($product->images)) {
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }
        
        // Delete health certificate
        if ($product->health_certificate) {
            Storage::disk('public')->delete($product->health_certificate);
        }
        
        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus!');
    }
}