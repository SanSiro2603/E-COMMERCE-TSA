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
}