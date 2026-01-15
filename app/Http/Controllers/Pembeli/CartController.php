<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Hapus item keranjang yang produknya sudah dihapus admin
        $carts = Cart::with(['product.category'])->where('user_id', Auth::id())->get();
        $carts->each(function ($cart) {
            if (!$cart->product || !$cart->product->is_active) { 
                $cart->delete(); 
            }
        });

        // Refresh data
        $carts = Cart::with(['product.category'])->where('user_id', Auth::id())->get();
        $total = $carts->sum('subtotal');

        return view('pembeli.keranjang.index', compact('carts', 'total'));
    }

    public function tambah(Request $request, $productId)
    {
        try {
            $product = Product::where('is_active', true)->findOrFail($productId);
            $userId = Auth::id();
            
            $quantity = $request->input('quantity', 1);

            // Cek apakah user sudah punya produk ini di keranjang
            $cart = Cart::where('user_id', $userId)->where('product_id', $productId)->first();
            $currentQty = $cart ? $cart->quantity : 0;
            $newTotalQty = $currentQty + $quantity;

            // 1. CEK STOK (Hanya Cek, JANGAN KURANGI DATABASE)
            if ($newTotalQty > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok tidak cukup! Tersedia: {$product->stock}"
                ], 400);
            }

            // 2. Simpan ke Keranjang
            if ($cart) {
                $cart->quantity = $newTotalQty;
                $cart->subtotal = $product->price * $newTotalQty;
                $cart->save();
            } else {
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ]);
            }

            // PENTING: TIDAK ADA $product->decrement() DISINI!
            
            $cartCount = Cart::where('user_id', $userId)->count();

            return response()->json([
                'success' => true,
                'message' => 'Produk masuk keranjang',
                'cart_count' => $cartCount
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $cartId)
    {
        try {
            $cart = Cart::with('product')->where('user_id', Auth::id())->findOrFail($cartId);
            $newQty = $request->quantity;

            // 1. CEK STOK REAL-TIME
            if ($newQty > $cart->product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok maksimal: {$cart->product->stock}"
                ], 400);
            }

            // 2. Update Keranjang
            $cart->update([
                'quantity' => $newQty,
                'subtotal' => $cart->product->price * $newQty
            ]);

            // Hitung ulang total
            $carts = Cart::where('user_id', Auth::id())->get();
            
            return response()->json([
                'success' => true,
                'subtotal' => number_format($cart->subtotal, 0, ',', '.'),
                'total' => number_format($carts->sum('subtotal'), 0, ',', '.'),
                'cart_count' => $carts->count()
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error'], 500);
        }
    }

    public function hapus($cartId)
    {
        Cart::where('user_id', Auth::id())->where('id', $cartId)->delete();
        // TIDAK ADA Increment Stok Disini (Karena stok memang belum diambil)

        $carts = Cart::where('user_id', Auth::id())->get();
        return response()->json([
            'success' => true,
            'cart_count' => $carts->count(),
            'total' => number_format($carts->sum('subtotal'), 0, ',', '.')
        ]);
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Keranjang dibersihkan');
    }

    public function count()
    {
        return response()->json(['count' => Cart::where('user_id', Auth::id())->count()]);
    }
}