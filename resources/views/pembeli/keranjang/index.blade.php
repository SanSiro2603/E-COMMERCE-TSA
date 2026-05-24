{{-- resources/views/pembeli/keranjang/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Keranjang Belanja - Tunas Sejahtera Adi Perkasa')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-primary transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-[#0d1b13] dark:text-white font-medium">Keranjang Belanja</span>
    </nav>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#0d1b13] dark:text-white">
                Keranjang Belanja
            </h1>
            <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1" id="cart-header-count">
                {{ $carts->count() }} item dalam keranjang Anda
            </p>
        </div>
    </div>

    @if($carts->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                
                <!-- Select All Header -->
                <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" 
                                   id="selectAll" 
                                   onchange="toggleSelectAll()"
                                   class="w-5 h-5 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary focus:ring-2 dark:bg-zinc-800 dark:border-zinc-700 cursor-pointer">
                            <span class="text-sm font-semibold text-[#0d1b13] dark:text-white">
                                Pilih Semua (<span id="selected-count">0</span>/{{ $carts->count() }})
                            </span>
                        </label>
                        
                        <button onclick="deleteSelected()" 
                                id="deleteSelectedBtn"
                                class="hidden items-center gap-2 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-sm sm:text-lg">delete</span>
                            Hapus Dipilih
                        </button>
                    </div>
                </div>

                @foreach($carts as $cart)
                    <div class="bg-white dark:bg-background-dark rounded-2xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm hover:shadow-md transition-shadow overflow-hidden cart-item" 
                          data-cart-id="{{ $cart->id }}" 
                          data-product-id="{{ $cart->product_id }}"
                          data-price="{{ $cart->product->price }}"
                          data-quantity="{{ $cart->quantity }}">
                        <div class="p-4 sm:p-5">
                            <div class="flex gap-3 sm:gap-4 items-start sm:items-center">
                                
                                <!-- Checkbox -->
                                <div class="flex items-center self-center sm:self-auto shrink-0">
                                    <input type="checkbox" 
                                           class="item-checkbox w-5 h-5 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary focus:ring-2 dark:bg-zinc-800 dark:border-zinc-700 cursor-pointer"
                                           data-cart-id="{{ $cart->id }}"
                                           onchange="updateSelectAll()">
                                </div>
                                
                                <!-- Product Image -->
                                <a href="{{ route('pembeli.produk.show', $cart->product->slug) }}" class="flex-shrink-0">
                                    <div class="w-20 sm:w-[88px] h-20 sm:h-[88px] bg-gray-100 dark:bg-zinc-800 rounded-xl overflow-hidden ring-1 ring-[#cfe7d9] dark:ring-primary/20">
                                        @if($cart->product->image)
                                            <img src="{{ asset('storage/' . $cart->product->image) }}" 
                                                 alt="{{ $cart->product->name }}" 
                                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-3xl">image</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                <!-- Product Info (Shopee Style) -->
                                <div class="flex-1 min-w-0 flex flex-col h-20 sm:h-auto justify-between">
                                    <!-- Name & Category -->
                                    <div>
                                        <a href="{{ route('pembeli.produk.show', $cart->product->slug) }}" class="block">
                                            <h3 class="text-xs sm:text-base font-semibold text-[#0d1b13] dark:text-white hover:text-primary transition-colors line-clamp-1 sm:line-clamp-2 leading-tight">
                                                {{ $cart->product->name }}
                                            </h3>
                                        </a>
                                        <span class="inline-flex items-center gap-1 mt-0.5 sm:mt-1 px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400 text-[10px] sm:text-xs font-medium">
                                            <span class="material-symbols-outlined" style="font-size:11px">category</span>
                                            {{ $cart->product->category->name ?? 'Uncategorized' }}
                                        </span>
                                    </div>

                                    <!-- Price & Quantity -->
                                    <div class="flex items-center justify-between mt-1 sm:mt-2 sm:pt-2 sm:border-t sm:border-dashed sm:border-[#cfe7d9] dark:sm:border-primary/20">
                                        <!-- Price -->
                                        <div>
                                            <span class="hidden sm:block text-[11px] font-medium text-gray-400 dark:text-zinc-500 uppercase tracking-wide">Harga Satuan</span>
                                            <p class="text-xs sm:text-base font-bold text-primary">
                                                Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center gap-1 bg-gray-50 dark:bg-zinc-800 border border-[#cfe7d9] dark:border-primary/20 rounded-lg p-0.5">
                                            <button onclick="updateQuantity({{ $cart->id }}, {{ $cart->product_id }}, -1, {{ $cart->product->stock }})" 
                                                    class="w-6 h-6 flex items-center justify-center text-gray-500 hover:text-primary hover:bg-primary/10 rounded transition-colors">
                                                <span class="material-symbols-outlined text-xs">remove</span>
                                            </button>
                                            <input type="number" 
                                                   value="{{ $cart->quantity }}" 
                                                   min="1"
                                                   max="{{ $cart->product->stock }}"
                                                   class="w-8 sm:w-10 text-center bg-transparent border-0 text-xs font-bold text-[#0d1b13] dark:text-white focus:ring-0 p-0"
                                                   onchange="updateQuantityDirect({{ $cart->id }}, {{ $cart->product_id }}, this.value, {{ $cart->product->stock }})"
                                                   id="quantity-{{ $cart->id }}">
                                            <button onclick="updateQuantity({{ $cart->id }}, {{ $cart->product_id }}, 1, {{ $cart->product->stock }})" 
                                                    class="w-6 h-6 flex items-center justify-center text-gray-500 hover:text-primary hover:bg-primary/10 rounded transition-colors">
                                                <span class="material-symbols-outlined text-xs">add</span>
                                            </button>
                                        </div>

                                        <!-- Subtotal (Desktop Only) -->
                                        <div class="hidden sm:block text-right">
                                            <p class="text-[11px] font-medium text-gray-400 dark:text-zinc-500 uppercase tracking-wide">Subtotal</p>
                                            <p class="text-base font-bold text-[#0d1b13] dark:text-white mt-0.5" id="subtotal-{{ $cart->id }}">
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

            <!-- Order Summary -->
            <div class="hidden lg:block lg:col-span-1">
                <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm sticky top-20">
                    <div class="p-4 sm:p-6 border-b border-[#cfe7d9] dark:border-primary/20">
                        <h2 class="text-lg font-semibold text-[#0d1b13] dark:text-white">Ringkasan Belanja</h2>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-zinc-400">Total Item Dipilih</span>
                            <span class="font-semibold text-[#0d1b13] dark:text-white" id="total-items-selected">0 item</span>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-zinc-400">Total Harga</span>
                            <span class="font-semibold text-[#0d1b13] dark:text-white" id="total-price-selected">
                                Rp 0
                            </span>
                        </div>

                        <div class="pt-4 border-t border-[#cfe7d9] dark:border-primary/20">
                            <div class="flex justify-between mb-2">
                                <span class="text-base font-semibold text-[#0d1b13] dark:text-white">Total Bayar</span>
                                <span class="text-xl font-bold text-primary" id="grand-total-selected">
                                    Rp 0
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-2">
                                *Pilih item untuk checkout
                            </p>
                        </div>
                    </div>

                    <div class="p-4 sm:p-6 border-t border-[#cfe7d9] dark:border-primary/20 space-y-3">
                        <button onclick="checkoutSelected()" 
                                id="checkoutBtn"
                                disabled
                                class="block w-full px-6 py-3 bg-primary text-white font-semibold rounded-xl text-center hover:shadow-lg hover:bg-primary/90 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">shopping_bag</span>
                                <span id="checkoutBtnText">Pilih Item Dulu</span>
                            </span>
                        </button>
                        <a href="{{ route('pembeli.produk.index') }}" 
                           class="block w-full px-6 py-3 bg-gray-100 dark:bg-zinc-800 text-[#0d1b13] dark:text-white font-medium rounded-xl text-center hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors">
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
        <!-- Empty Cart -->
        <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm">
            <div class="text-center py-16 px-4">
                <div class="w-24 h-24 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl">shopping_cart</span>
                </div>
                <h3 class="text-xl font-bold text-[#0d1b13] dark:text-white mb-2">
                    Keranjang Belanja Kosong
                </h3>
                <p class="text-gray-600 dark:text-zinc-400 mb-6">
                    Yuk, mulai belanja dan temukan produk terbaik!
                </p>
                <a href="{{ route('pembeli.produk.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-medium rounded-lg hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined">storefront</span>
                    Mulai Belanja
                </a>
            </div>
        </div>
    @endif

    <!-- Sticky Mobile Bottom Bar (Shopee Style) -->
    <div id="mobileStickyBar" class="sm:hidden fixed bottom-0 left-0 right-0 z-40 bg-white dark:bg-background-dark border-t border-[#cfe7d9] dark:border-primary/20 shadow-2xl px-4 py-3 flex items-center justify-between pb-safe transform transition-transform duration-300 translate-y-full">
        <div class="flex items-center gap-2">
            <label class="flex items-center gap-1.5 cursor-pointer">
                <input type="checkbox" 
                       id="selectAllMobile" 
                       onchange="toggleSelectAllMobile()"
                       class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:bg-zinc-800 dark:border-zinc-700 cursor-pointer">
                <span class="text-xs font-semibold text-[#0d1b13] dark:text-white">Semua</span>
            </label>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right">
                <p class="text-[10px] text-gray-500 dark:text-zinc-400">Total Bayar</p>
                <p class="text-sm font-bold text-primary" id="mobile-grand-total">Rp 0</p>
            </div>
            <button onclick="checkoutSelected()" 
                    id="checkoutBtnMobile"
                    disabled
                    class="px-5 py-2 bg-primary text-white text-xs font-bold rounded-lg disabled:opacity-50 disabled:cursor-not-allowed">
                Checkout (<span id="mobile-selected-count">0</span>)
            </button>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Toggle Select All
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateSelectAll();
    }

    function toggleSelectAllMobile() {
        const selectAllMobile = document.getElementById('selectAllMobile');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAllMobile.checked);
        updateSelectAll();
    }

    // Update Select All state & Calculate Summary
    function updateSelectAll() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const checked = document.querySelectorAll('.item-checkbox:checked');
        
        const selectAll = document.getElementById('selectAll');
        const selectAllMobile = document.getElementById('selectAllMobile');
        
        const selectedCount = document.getElementById('selected-count');
        const mobileSelectedCount = document.getElementById('mobile-selected-count');
        
        const deleteBtn = document.getElementById('deleteSelectedBtn');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const checkoutBtnMobile = document.getElementById('checkoutBtnMobile');
        const checkoutBtnText = document.getElementById('checkoutBtnText');
        
        // Update select all checkboxes
        const isAllChecked = checkboxes.length === checked.length;
        if (selectAll) selectAll.checked = isAllChecked;
        if (selectAllMobile) selectAllMobile.checked = isAllChecked;
        
        if (selectedCount) selectedCount.textContent = checked.length;
        if (mobileSelectedCount) mobileSelectedCount.textContent = checked.length;
        
        // Show/Hide Delete Button
        if (checked.length > 0) {
            deleteBtn.classList.remove('hidden');
            deleteBtn.classList.add('flex');
        } else {
            deleteBtn.classList.add('hidden');
            deleteBtn.classList.remove('flex');
        }
        
        // Calculate Selected Items Total
        let totalQuantity = 0;
        let totalPrice = 0;
        
        checked.forEach(checkbox => {
            const cartId = checkbox.dataset.cartId;
            const item = document.querySelector(`[data-cart-id="${cartId}"]`);
            const price = parseFloat(item.dataset.price);
            const quantity = parseInt(item.dataset.quantity);
            
            totalQuantity += quantity;
            totalPrice += price * quantity;
        });
        
        // Update Summary
        document.getElementById('total-items-selected').textContent = `${totalQuantity} item`;
        document.getElementById('total-price-selected').textContent = `Rp ${formatNumber(totalPrice)}`;
        document.getElementById('grand-total-selected').textContent = `Rp ${formatNumber(totalPrice)}`;
        
        const mobileGrandTotal = document.getElementById('mobile-grand-total');
        if (mobileGrandTotal) {
            mobileGrandTotal.textContent = `Rp ${formatNumber(totalPrice)}`;
        }
        
        // Enable/Disable Checkout Button & Slide Mobile Sticky Bar
        const mobileStickyBar = document.getElementById('mobileStickyBar');
        if (checked.length > 0) {
            if (checkoutBtn) {
                checkoutBtn.disabled = false;
                checkoutBtnText.textContent = `Checkout (${checked.length} item)`;
            }
            if (checkoutBtnMobile) {
                checkoutBtnMobile.disabled = false;
            }
            if (mobileStickyBar) {
                mobileStickyBar.classList.remove('translate-y-full');
            }
        } else {
            if (checkoutBtn) {
                checkoutBtn.disabled = true;
                checkoutBtnText.textContent = 'Pilih Item Dulu';
            }
            if (checkoutBtnMobile) {
                checkoutBtnMobile.disabled = true;
            }
            if (mobileStickyBar) {
                mobileStickyBar.classList.add('translate-y-full');
            }
        }
    }
    
    // Format Number with dot separator
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Delete Selected Items
    function deleteSelected() {
        const checked = Array.from(document.querySelectorAll('.item-checkbox:checked'));
        const cartIds = checked.map(cb => cb.dataset.cartId);
        
        if (cartIds.length === 0) return;
        
        Swal.fire({
            title: 'Hapus Item Terpilih?',
            text: `${cartIds.length} item akan dihapus dari keranjang`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#13ec6d',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteMultipleItems(cartIds);
            }
        });
    }

    function deleteMultipleItems(cartIds) {
        const promises = cartIds.map(id => 
            fetch(`/pembeli/keranjang/hapus/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(r => r.json())
        );
        
        Promise.all(promises).then(() => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Item terpilih telah dihapus',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        });
    }

    // Update Quantity
    function updateQuantity(cartId, productId, change, currentStock) {
        const input = document.getElementById(`quantity-${cartId}`);
        const currentQty = parseInt(input.value);
        let newValue = currentQty + change;

        if (newValue < 1) return;

        if (newValue > currentStock) {
            Swal.fire({
                title: 'Stok Tidak Cukup!',
                text: `Maksimal stok: ${currentStock}`,
                icon: 'warning',
                confirmButtonColor: '#13ec6d'
            });
            return;
        }

        input.value = newValue;
        saveQuantity(cartId, productId, newValue);
    }

    function updateQuantityDirect(cartId, productId, value, currentStock) {
        let newValue = parseInt(value) || 1;

        if (newValue > currentStock) {
            Swal.fire({
                title: 'Stok Tidak Cukup!',
                text: `Maksimal stok: ${currentStock}`,
                icon: 'warning',
                confirmButtonColor: '#13ec6d'
            });
            newValue = currentStock;
        }

        document.getElementById(`quantity-${cartId}`).value = newValue;
        saveQuantity(cartId, productId, newValue);
    }

    function saveQuantity(cartId, productId, quantity) {
        fetch(`/pembeli/keranjang/update/${cartId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Update subtotal display
                document.getElementById(`subtotal-${cartId}`).textContent = `Rp ${data.subtotal}`;
                
                // Update data-quantity attribute for recalculation
                const item = document.querySelector(`[data-cart-id="${cartId}"]`);
                item.dataset.quantity = quantity;
                
                // Update cart count in header
                if (typeof updateCartCount === 'function') {
                    updateCartCount(data.cart_count);
                }
                
                // Recalculate selected items summary
                updateSelectAll();
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: data.message || 'Gagal memperbarui jumlah',
                    icon: 'error',
                    confirmButtonColor: '#13ec6d'
                });
                location.reload();
            }
        })
        .catch(() => {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan',
                icon: 'error',
                confirmButtonColor: '#13ec6d'
            });
            location.reload();
        });
    }

    // Checkout Selected Items
    function checkoutSelected() {
        const checked = Array.from(document.querySelectorAll('.item-checkbox:checked'));
        
        if (checked.length === 0) {
            Swal.fire({
                title: 'Pilih Item!',
                text: 'Silakan pilih item yang ingin di-checkout',
                icon: 'warning',
                confirmButtonColor: '#13ec6d'
            });
            return;
        }
        
        // Kirim cart IDs yang dipilih ke server (simpan di session)
        const cartIds = checked.map(cb => cb.dataset.cartId);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("pembeli.keranjang.checkout-selected") }}';
        
        // CSRF token
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrf);
        
        // Append each selected cart ID
        cartIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'cart_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }

    // Clear Cart
    function clearCart() {
        Swal.fire({
            title: 'Kosongkan Keranjang?',
            text: 'Semua produk akan dihapus dari keranjang',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#13ec6d',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kosongkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/pembeli/keranjang/clear';
            }
        });
    }
</script>
@endpush
@endsection