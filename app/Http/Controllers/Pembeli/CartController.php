<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['product.category'])
            ->where('user_id', Auth::id())
            ->get();

        $carts->each(function ($cart) {
            if (!$cart->product || !$cart->product->is_active) {
                $cart->delete();
            }
        });

        $carts = Cart::with(['product.category'])
            ->where('user_id', Auth::id())
            ->get();

        $total = $carts->sum('subtotal');

        return view('pembeli.keranjang.index', compact('carts', 'total'));
    }

    public function tambah(Request $request, $productId)
    {
        try {
            DB::transaction(function () use ($request, $productId, &$product, &$cart) {
                $product = Product::where('is_active', true)->findOrFail($productId);

                $validated = $request->validate([
                    'quantity' => 'nullable|integer|min:1',
                ]);

                $quantity = $validated['quantity'] ?? 1;

                $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->first();

                $currentQty = $cart ? $cart->quantity : 0;
                $newTotalQty = $currentQty + $quantity;

                // PERBAIKAN: Stok tersedia = stok tersisa + qty di cart
                $availableStock = $product->stock + $currentQty;

                if ($newTotalQty > $availableStock) {
                    abort(response()->json([
                        'success' => false,
                        'message' => "Stok tidak mencukupi. Tersedia: {$availableStock}, Dibutuhkan: {$newTotalQty}"
                    ], 400));
                }

                if ($cart) {
                    $cart->quantity = $newTotalQty;
                    $cart->subtotal = $product->price * $newTotalQty;
                    $cart->save();
                } else {
                    $cart = Cart::create([
                        'user_id' => Auth::id(),
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'subtotal' => $product->price * $quantity,
                    ]);
                }

                $product->decrement('stock', $quantity);
            });

            $cartCount = Cart::where('user_id', Auth::id())->count();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart_count' => $cartCount,
                'cart' => $cart,
                'new_stock' => $product->stock,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $cartId)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $cart = Cart::where('user_id', Auth::id())->findOrFail($cartId);
            $product = $cart->product;

            $oldQuantity = $cart->quantity;
            $newQuantity = $validated['quantity'];

            // PERBAIKAN: Stok tersedia = stok tersisa + qty lama
            $availableStock = $product->stock + $oldQuantity;

            if ($newQuantity > $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok tidak mencukupi. Tersedia: {$availableStock}, Dibutuhkan: {$newQuantity}"
                ], 400);
            }

            DB::transaction(function () use ($cart, $product, $newQuantity, $oldQuantity) {
                $quantityDiff = $newQuantity - $oldQuantity;

                $cart->quantity = $newQuantity;
                $cart->subtotal = $product->price * $newQuantity;
                $cart->save();

                if ($quantityDiff > 0) {
                    $product->decrement('stock', $quantityDiff);
                } elseif ($quantityDiff < 0) {
                    $product->increment('stock', abs($quantityDiff));
                }
            });

            $carts = Cart::where('user_id', Auth::id())->get();
            $total = $carts->sum('subtotal');
            $cartCount = $carts->count();
            $totalItems = $carts->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Jumlah produk berhasil diperbarui',
                'subtotal' => number_format($cart->subtotal, 0, ',', '.'),
                'total' => number_format($total, 0, ',', '.'),
                'new_stock' => $product->stock,
                'cart_count' => $cartCount,
                'total_items' => $totalItems,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui keranjang: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clear()
    {
        try {
            Cart::where('user_id', Auth::id())->delete();
            return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengosongkan keranjang: ' . $e->getMessage());
        }
    }

    public function count()
    {
        $count = Cart::where('user_id', Auth::id())->count();
        return response()->json(['count' => $count]);
    }

    public function getStock($productId)
    {
        $product = Product::select('stock')->find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'stock' => $product->stock]);
    }

    public function updateStockAfterAdd($productId, Request $request)
    {
        $quantity = $request->input('quantity', 1);
        $product = Product::find($productId);
        if (!$product) return response()->json(['success' => false], 404);
        if ($product->stock < $quantity) {
            return response()->json(['success' => false, 'message' => 'Stok tidak cukup'], 400);
        }
        $product->decrement('stock', $quantity);
        return response()->json(['success' => true, 'new_stock' => $product->stock]);
    }
}