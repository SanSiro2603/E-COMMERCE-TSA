{{-- resources/views/pembeli/keranjang/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Keranjang Belanja - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Success/Error Alerts -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-lg p-4 animate-fade-in">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-green-900 dark:text-green-300">Berhasil!</h3>
                    <p class="text-sm text-green-800 dark:text-green-400 mt-1">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4 animate-fade-in">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">error</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-900 dark:text-red-300">Gagal!</h3>
                    <p class="text-sm text-red-800 dark:text-red-400 mt-1">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-soft-green transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Keranjang Belanja</span>
    </nav>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">
                Keranjang Belanja
            </h1>
            <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
                {{ $carts->count() }} item dalam keranjang Anda
            </p>
        </div>
        @if($carts->count() > 0)
            <button onclick="clearCart()" 
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-lg">delete</span>
                Kosongkan Keranjang
            </button>
        @endif
    </div>

    @if($carts->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Cart Items (Left - 2/3) -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($carts as $cart)
                    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden cart-item" data-cart-id="{{ $cart->id }}">
                        <div class="p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <!-- Product Image -->
                                <a href="{{ route('pembeli.produk.show', $cart->product->slug) }}" 
                                   class="flex-shrink-0">
                                    <div class="w-full sm:w-24 h-24 bg-gray-100 dark:bg-zinc-800 rounded-lg overflow-hidden">
                                        @if($cart->product->image)
                                            <img src="{{ asset('storage/' . $cart->product->image) }}" 
                                                 alt="{{ $cart->product->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-3xl">image</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-4 mb-2">
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('pembeli.produk.show', $cart->product->slug) }}" 
                                               class="block">
                                                <h3 class="text-base font-semibold text-gray-900 dark:text-white hover:text-soft-green transition-colors line-clamp-2">
                                                    {{ $cart->product->name }}
                                                </h3>
                                            </a>
                                            <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-sm">category</span>
                                                    {{ $cart->product->category->name ?? 'Uncategorized' }}
                                                </span>
                                            </p>
                                        </div>
                                        <button onclick="removeFromCart({{ $cart->id }})" 
                                                class="flex-shrink-0 text-gray-400 hover:text-red-500 transition-colors">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </div>

                                    <!-- Price & Quantity -->
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4">
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-zinc-400">Harga Satuan</p>
                                            <p class="text-lg font-bold text-soft-green">
                                                Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center gap-2 bg-gray-50 dark:bg-zinc-800 rounded-lg p-1">
                                                <button onclick="updateQuantity({{ $cart->id }}, -1, {{ $cart->product->stock }})" 
                                                        class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                                                    <span class="material-symbols-outlined text-sm">remove</span>
                                                </button>
                                                <input type="number" 
                                                       value="{{ $cart->quantity }}" 
                                                       min="1"
                                                       max="{{ $cart->product->stock }}"
                                                       class="w-16 text-center bg-transparent border-0 text-sm font-semibold text-gray-900 dark:text-white focus:ring-0"
                                                       onchange="updateQuantityDirect({{ $cart->id }}, this.value, {{ $cart->product->stock }})"
                                                       id="quantity-{{ $cart->id }}">
                                                <button onclick="updateQuantity({{ $cart->id }}, 1, {{ $cart->product->stock }})" 
                                                        class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                                                    <span class="material-symbols-outlined text-sm">add</span>
                                                </button>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-zinc-400">
                                                Stok: {{ $cart->product->stock }}
                                            </span>
                                        </div>

                                        <!-- Subtotal -->
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500 dark:text-zinc-400">Subtotal</p>
                                            <p class="text-lg font-bold text-gray-900 dark:text-white" id="subtotal-{{ $cart->id }}">
                                                Rp {{ number_format($cart->subtotal, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary (Right - 1/3) -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm sticky top-20">
                    <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Belanja</h2>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-zinc-400">Total Item</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $carts->sum('quantity') }} item</span>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-zinc-400">Total Harga</span>
                            <span class="font-semibold text-gray-900 dark:text-white" id="total-price">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="pt-4 border-t border-gray-200 dark:border-zinc-800">
                            <div class="flex justify-between mb-2">
                                <span class="text-base font-semibold text-gray-900 dark:text-white">Total Bayar</span>
                                <span class="text-xl font-bold text-soft-green" id="grand-total">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 dark:border-zinc-800 space-y-3">
                        {{-- <a href="{{ route('pembeli.checkout') }}" 
                           class="block w-full px-6 py-3 bg-gradient-to-r from-soft-green to-primary text-white font-semibold rounded-xl text-center hover:shadow-lg transition-all">
                            <span class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">shopping_bag</span>
                                Lanjut ke Checkout
                            </span>
                        </a> --}}
                        <a href="{{ route('pembeli.produk.index') }}" 
                           class="block w-full px-6 py-3 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-medium rounded-xl text-center hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors">
                            <span class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">storefront</span>
                                Lanjut Belanja
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart State -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="text-center py-16 px-4">
                <div class="w-24 h-24 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl">shopping_cart</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Keranjang Belanja Kosong
                </h3>
                <p class="text-gray-600 dark:text-zinc-400 mb-6">
                    Yuk, mulai belanja dan temukan produk terbaik!
                </p>
                <a href="{{ route('pembeli.produk.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-soft-green to-primary text-white font-medium rounded-lg hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined">storefront</span>
                    Mulai Belanja
                </a>
            </div>
        </div>
    @endif

</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>

<script>
    function updateQuantity(cartId, change, maxStock) {
        const input = document.getElementById(`quantity-${cartId}`);
        let newValue = parseInt(input.value) + change;
        
        if (newValue < 1) newValue = 1;
        if (newValue > maxStock) {
            alert(`Maksimal stok: ${maxStock}`);
            return;
        }
        
        input.value = newValue;
        saveQuantity(cartId, newValue);
    }

    function updateQuantityDirect(cartId, value, maxStock) {
        let newValue = parseInt(value);
        
        if (newValue < 1) newValue = 1;
        if (newValue > maxStock) {
            alert(`Maksimal stok: ${maxStock}`);
            newValue = maxStock;
        }
        
        document.getElementById(`quantity-${cartId}`).value = newValue;
        saveQuantity(cartId, newValue);
    }

    function saveQuantity(cartId, quantity) {
        fetch(`/pembeli/keranjang/update/${cartId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`subtotal-${cartId}`).textContent = `Rp ${data.subtotal}`;
                document.getElementById('total-price').textContent = `Rp ${data.total}`;
                document.getElementById('grand-total').textContent = `Rp ${data.total}`;
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memperbarui jumlah');
        });
    }

    function removeFromCart(cartId) {
        if (!confirm('Hapus produk dari keranjang?')) return;
        
        fetch(`/pembeli/keranjang/hapus/${cartId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`[data-cart-id="${cartId}"]`).remove();
                
                if (data.cart_count === 0) {
                    location.reload();
                } else {
                    document.getElementById('total-price').textContent = `Rp ${data.total}`;
                    document.getElementById('grand-total').textContent = `Rp ${data.total}`;
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghapus produk');
        });
    }

    function clearCart() {
        if (!confirm('Yakin ingin mengosongkan keranjang?')) return;
        
        window.location.href = '{{ route("pembeli.keranjang.clear") }}';
    }
</script>
@endsection