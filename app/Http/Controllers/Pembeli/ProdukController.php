<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();
        
        $query = Product::with('category')
            ->where('is_active', true)
            ->latest();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        $products = $query->paginate(12);

        // Handle AJAX request
        if ($request->ajax() || $request->has('ajax')) {
            $html = $this->generateProductsGrid($products);
            
            // Get active category name
            $categoryName = null;
            if ($request->filled('category')) {
                $category = $categories->firstWhere('id', $request->category);
                $categoryName = $category ? $category->name : null;
            }

            return response()->json([
                'html' => $html,
                'count' => $products->count(),
                'total' => $products->total(),
                'filters' => [
                    'search' => $request->search,
                    'category' => $request->category,
                    'categoryName' => $categoryName,
                ],
            ]);
        }

        return view('pembeli.produk.index', compact('products', 'categories'));
    }

    private function generateProductsGrid($products)
    {
        if ($products->isEmpty()) {
            return '
                <div class="col-span-full bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
                    <div class="text-center py-16 px-4">
                        <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-8xl mb-4">search_off</span>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            Produk Tidak Ditemukan
                        </h3>
                        <p class="text-gray-600 dark:text-zinc-400 mb-6">
                            Maaf, tidak ada produk yang sesuai dengan pencarian Anda
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="' . route('pembeli.produk.index') . '" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-soft-green to-primary text-white font-medium rounded-lg hover:shadow-lg transition-all">
                                <span class="material-symbols-outlined">refresh</span>
                                Reset Filter
                            </a>
                            <a href="' . route('pembeli.dashboard') . '" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors">
                                <span class="material-symbols-outlined">home</span>
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            ';
        }

        $html = '';
        foreach ($products as $product) {
            $stockBadge = '';
            $imageOverlay = '';
            
            if ($product->stock <= 5 && $product->stock > 0) {
                $stockBadge = '<div class="absolute top-2 right-2 px-2 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full">Stok ' . $product->stock . '</div>';
            } elseif ($product->stock == 0) {
                $imageOverlay = '<div class="absolute inset-0 bg-black/50 flex items-center justify-center"><span class="px-3 py-1.5 bg-red-500 text-white text-sm font-bold rounded-full">Habis</span></div>';
            }

            $actionButtons = '';
            if ($product->stock > 0) {
                $actionButtons = '
                    <button onclick="addToCart(' . $product->id . ')"
                            class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-gradient-to-r from-soft-green to-primary text-white rounded-lg text-xs font-medium hover:shadow-md transition-all">
                        <span class="material-symbols-outlined text-base">shopping_cart</span>
                        <span class="hidden sm:inline">Keranjang</span>
                    </button>
                    <a href="' . route('pembeli.produk.show', $product->slug) . '"
                       class="px-3 py-2 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-base">visibility</span>
                    </a>
                ';
            } else {
                $actionButtons = '
                    <button disabled
                            class="flex-1 px-3 py-2 bg-gray-200 dark:bg-zinc-800 text-gray-400 dark:text-zinc-500 rounded-lg text-xs font-medium cursor-not-allowed">
                        Stok Habis
                    </button>
                ';
            }

            $image = $product->image 
                ? '<img src="' . asset('storage/' . $product->image) . '" alt="' . e($product->name) . '" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">'
                : '<div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl">image</span></div>';

            $description = $product->description 
                ? '<p class="text-xs text-gray-600 dark:text-zinc-400 line-clamp-2 mb-3">' . e($product->description) . '</p>'
                : '';

            $html .= '
    <div class="group bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm hover:shadow-lg transition-all overflow-hidden">
        <a href="' . route('pembeli.produk.show', $product->slug) . '" 
           class="block relative overflow-hidden bg-gray-100 dark:bg-zinc-800 rounded-t-lg">
            ' . ($product->image 
                ? '<img src="' . asset('storage/' . $product->image) . '" alt="' . e($product->name) . '" class="block w-[110%] max-w-none -ml-[5%] object-cover transition-transform duration-300 hover:scale-110 rounded-t-lg product-image">'
                : '<div class="w-[110%] max-w-none -ml-[5%] flex items-center justify-center product-image"><span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl">image</span></div>') . '
            ' . $stockBadge . '
            ' . $imageOverlay . '
        </a>
        <div class="p-4">
            <div class="mb-2">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 text-xs font-medium rounded-full">
                    <span class="material-symbols-outlined text-xs">category</span>
                    ' . e($product->category->name ?? 'Uncategorized') . '
                </span>
            </div>
            <a href="' . route('pembeli.produk.show', $product->slug) . '" class="block">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2 group-hover:text-soft-green transition-colors mb-2">
                    ' . e($product->name) . '
                </h3>
            </a>
            ' . $description . '
            <div class="mb-3">
                <p class="text-lg font-bold text-soft-green dark:text-soft-green">
                    Rp ' . number_format($product->price, 0, ',', '.') . '
                </p>
            </div>
            <div class="flex gap-2">
                ' . $actionButtons . '
            </div>
        </div>
    </div>
';

        }

        return $html;
    }

    public function show($slug)
    {
        $product = Product::with(['category'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Related products
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('pembeli.produk.show', compact('product', 'relatedProducts'));
    }
}