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
        // Kategori utama saja untuk filter baris 1
        $parentCategories = Category::parentOnly()
            ->where('is_active', true)
            ->whereHas('products', fn($q) => $q->where('is_active', true))
            ->orWhereHas('children.products', fn($q) => $q->where('is_active', true))
            ->with(['children' => fn($q) => $q->where('is_active', true)
                ->whereHas('products', fn($q2) => $q2->where('is_active', true))])
            ->orderBy('name')
            ->get();

            // Sort
            $sortField = 'created_at';
            $sortDir   = 'desc';

            if ($request->filled('sort')) {
                match($request->sort) {
                    'cheapest'  => [$sortField, $sortDir] = ['price', 'asc'],
                    'expensive' => [$sortField, $sortDir] = ['price', 'desc'],
                    'newest'    => [$sortField, $sortDir] = ['created_at', 'desc'],
                    default     => [$sortField, $sortDir] = ['created_at', 'desc'],
                };
            }

        $query = Product::with('category.parent')
            ->where('is_active', true)
            ->orderBy($sortField, $sortDir);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter kategori utama — tampilkan semua produk di bawahnya (termasuk sub)
        if ($request->filled('parent_category')) {
            $parentId = $request->parent_category;
            $query->whereHas('category', function($q) use ($parentId) {
                $q->where('id', $parentId)
                  ->orWhere('parent_id', $parentId);
            });
        }

        // Filter sub kategori — exact
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter harga
        if ($request->filled('min_price') || $request->filled('max_price')) {
            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }
        }

        $products = $query->paginate(24);

        if ($request->ajax() || $request->has('ajax')) {
            $html = view('pembeli.produk.partials.products-grid', compact('products'))->render();

            return response()->json([
                'success'      => true,
                'html'         => $html,
                'count'        => $products->count(),
                'total'        => $products->total(),
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
            ]);
        }

        return view('pembeli.produk.index', compact('products', 'parentCategories'));
    }

    public function autocomplete(Request $request)
{
    $query = $request->get('q', '');

    if (strlen($query) < 2) {
        return response()->json([]);
    }

    $products = Product::with('category.parent')
        ->where('is_active', true)
        ->where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        })
        ->select('id', 'name', 'slug', 'image', 'price', 'category_id')
        ->limit(6)
        ->get()
        ->map(function($product) {
            return [
                'id'            => $product->id,
                'name'          => $product->name,
                'slug'          => $product->slug,
                'image'         => $product->image ? asset('storage/' . $product->image) : null,
                'price'         => 'Rp ' . number_format($product->price, 0, ',', '.'),
                'category'      => $product->category->parent->name ?? $product->category->name ?? '',
                'sub_category'  => $product->category->parent ? $product->category->name : null,
                'url'           => route('pembeli.produk.show', $product->slug),
            ];
        });

    return response()->json($products);
}

    public function show($slug)
    {
        $product = Product::with(['category.parent'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $product = $this->processProductImages($product);

        $relatedProducts = Product::with('category.parent')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where('id', '!=', $product->id)
            ->where(function($q) use ($product) {
                // Related = sama sub kategori atau sama kategori utama
                $q->where('category_id', $product->category_id)
                  ->orWhereHas('category', function($q2) use ($product) {
                      $parentId = $product->category->parent_id ?? $product->category_id;
                      $q2->where('parent_id', $parentId)->orWhere('id', $parentId);
                  });
            })
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('pembeli.produk.show', compact('product', 'relatedProducts'));
    }

    private function processProductImages($product)
    {
        $images = [];

        if (isset($product->images) && !empty($product->images)) {
            if (is_string($product->images)) {
                $decoded = json_decode($product->images, true);
                if (is_array($decoded) && !empty($decoded)) {
                    $images = $decoded;
                }
            } elseif (is_array($product->images)) {
                $images = $product->images;
            }
        }

        if (empty($images) && !empty($product->image)) {
            $images = [$product->image];
        }

        $images = array_values(array_unique(array_filter($images, fn($img) => !empty($img) && is_string($img))));

        $product->images = $images;

        return $product;
    }
}