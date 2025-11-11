<?php
// app/Http/Controllers/Pembeli/ProdukController.php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display product catalog with search and filter
     */
    public function index(Request $request)
    {
        // Get all categories for filter (disesuaikan dengan model Anda)
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        // Start query (disesuaikan dengan field di database Anda)
        $query = Product::with('category')
            ->where('is_active', true)
            ->latest();
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        
        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Paginate results (24 untuk grid 6 kolom, 20 untuk 5 kolom, 12 untuk 4 kolom)
        $products = $query->paginate(24);
        
        // If AJAX request, return JSON with HTML
        if ($request->ajax() || $request->has('ajax')) {
            // Gunakan partial view untuk render HTML
            $html = view('pembeli.produk.partials.products-grid', compact('products'))->render();
            
            // Get active category name
            $categoryName = null;
            if ($request->filled('category')) {
                $category = $categories->firstWhere('id', $request->category);
                $categoryName = $category ? $category->name : null;
            }
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $products->count(),
                'total' => $products->total(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'filters' => [
                    'search' => $request->search,
                    'category' => $request->category,
                    'categoryName' => $categoryName,
                ],
            ]);
        }
        
        // Regular request, return view
        return view('pembeli.produk.index', compact('products', 'categories'));
    }
    
    /**
     * Display single product detail
     */
    public function show($slug)
    {
        // Disesuaikan dengan field database Anda
        $product = Product::with(['category'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        // Process images for gallery
        $product = $this->processProductImages($product);
        
        // Get related products
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->limit(6)
            ->get();
        
        return view('pembeli.produk.show', compact('product', 'relatedProducts'));
    }
    
    /**
     * Process product images for gallery display
     * 
     * @param Product $product
     * @return Product
     */
    private function processProductImages($product)
    {
        $images = [];
        
        // LANGKAH 1: Cek kolom 'images' (JSON array dari gallery)
        if (isset($product->images) && !empty($product->images)) {
            // Jika images adalah string JSON, decode dulu
            if (is_string($product->images)) {
                $decoded = json_decode($product->images, true);
                if (is_array($decoded) && !empty($decoded)) {
                    $images = $decoded;
                }
            } 
            // Jika sudah array (dari cast di Model)
            elseif (is_array($product->images)) {
                $images = $product->images;
            }
        }
        
        // LANGKAH 2: Jika tidak ada images ATAU kosong, gunakan image utama saja
        if (empty($images) && !empty($product->image)) {
            $images = [$product->image];
        }
        
        // LANGKAH 3: Filter images yang valid (tidak null/empty)
        $images = array_filter($images, function($img) {
            return !empty($img) && is_string($img);
        });
        
        // LANGKAH 4: Reset array keys dan pastikan unique
        $images = array_values(array_unique($images));
        
        // LANGKAH 5: Jika masih kosong, fallback ke array kosong
        if (empty($images)) {
            $images = [];
        }
        
        // Set ke product untuk digunakan di view
        $product->images = $images;
        
        // // Debug log (opsional, hapus di production)
        // \Log::info('Product Images Processed', [
        //     'product_id' => $product->id,
        //     'product_name' => $product->name,
        //     'raw_images_column' => $product->getOriginal('images'),
        //     'main_image' => $product->image,
        //     'processed_images' => $images,
        //     'total_images' => count($images)
        // ]);
        
        return $product;
    }
}