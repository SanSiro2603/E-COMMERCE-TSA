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

        // Hapus item dengan produk yang sudah dihapus atau nonaktif
        $carts->each(function ($cart) {
            if (!$cart->product || !$cart->product->is_active) {
                $cart->delete();
            }
        });

        // Reload data setelah bersihkan
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

                // Cek stok
                if ($product->stock < $quantity) {
                    abort(response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock
                    ], 400));
                }

                $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->first();

                if ($cart) {
                    $newQuantity = $cart->quantity + $quantity;
                    if ($product->stock < $newQuantity) {
                        abort(response()->json([
                            'success' => false,
                            'message' => 'Jumlah melebihi stok tersedia. Stok tersedia: ' . $product->stock
                        ], 400));
                    }

                    $cart->quantity = $newQuantity;
                    $cart->subtotal = $product->price * $newQuantity;
                    $cart->save();
                } else {
                    $cart = Cart::create([
                        'user_id' => Auth::id(),
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'subtotal' => $product->price * $quantity,
                    ]);
                }

                // Kurangi stok produk
                $product->decrement('stock', $quantity);
            });

            $cartCount = Cart::where('user_id', Auth::id())->count();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart_count' => $cartCount,
                'cart' => $cart,
                'new_stock' => $product->stock, // Stok terbaru
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk ke keranjang: ' . $e->getMessage()
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

            if ($product->stock < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock
                ], 400);
            }

            $cart->quantity = $validated['quantity'];
            $cart->subtotal = $product->price * $validated['quantity'];
            $cart->save();

            $total = Cart::where('user_id', Auth::id())->sum('subtotal');

            return response()->json([
                'success' => true,
                'message' => 'Jumlah produk berhasil diperbarui',
                'cart' => $cart,
                'subtotal' => number_format($cart->subtotal, 0, ',', '.'),
                'total' => number_format($total, 0, ',', '.'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui keranjang: ' . $e->getMessage()
            ], 500);
        }
    }

    public function hapus($cartId)
    {
        try {
            $cart = Cart::where('user_id', Auth::id())->findOrFail($cartId);
            $cart->delete();

            $cartCount = Cart::where('user_id', Auth::id())->count();
            $total = Cart::where('user_id', Auth::id())->sum('subtotal');

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang',
                'cart_count' => $cartCount,
                'total' => number_format($total, 0, ',', '.'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk: ' . $e->getMessage()
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
}