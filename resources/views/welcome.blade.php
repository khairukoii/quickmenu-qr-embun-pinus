<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu - Embun Pinus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; scroll-behavior: smooth; }
    .bottom-sheet { transform: translateY(100%); transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1); }
    .bottom-sheet.show { transform: translateY(0); }
    </style>
</head>
<body class="bg-[#F8F9FA] sm:bg-gray-200 text-gray-800 antialiased selection:bg-green-200">

    <div id="welcome-modal" class="fixed inset-0 bg-gray-900/90 z-[70] flex items-center justify-center backdrop-blur-md transition-opacity duration-500 px-4">
        <div class="bg-white w-full max-w-sm rounded-[35px] p-8 flex flex-col shadow-2xl transform transition-transform duration-500 scale-100 text-center">
            <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center text-5xl shadow-inner mx-auto mb-6 border-4 border-white shadow-[0_10px_20px_rgba(21,128,61,0.2)]">🌲</div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Hai, Selamat Datang!</h2>
            <p class="text-gray-500 font-medium mt-2 mb-6">Kamu sekarang berada di <span class="font-black text-green-600 text-lg">Meja {{ $meja ?? '?' }}</span></p>

            <div class="mb-8 text-left">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-2">Siapa nama panggilanmu?</label>
                <div class="relative flex items-center">
                    <span class="absolute left-5 text-xl text-gray-400">👤</span>
                    <input type="text" id="customer-name-input" placeholder="Ketik di sini..." class="w-full bg-[#F5F6F8] border-2 border-transparent focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-500/10 rounded-2xl py-4 pl-14 pr-4 outline-none transition-all font-bold text-gray-800 text-lg shadow-inner">
                </div>
                <p id="name-error" class="text-red-500 text-xs font-bold mt-2 ml-2 hidden animate-pulse">⚠️ Tolong isi nama kamu dulu ya!</p>
            </div>
            <button onclick="saveCustomerName()" class="w-full bg-green-600 text-white font-black text-lg py-4 rounded-2xl shadow-lg shadow-green-600/40 active:scale-95 transition-transform flex justify-center items-center gap-2">
                Lihat Menu Sekarang
            </button>
        </div>
    </div>

    <div class="w-full h-[100dvh] mx-auto sm:max-w-[480px] flex flex-col bg-[#F8F9FA] relative overflow-hidden sm:shadow-2xl">
      <header class="bg-white/90 backdrop-blur-xl px-6 pt-6 pb-4 rounded-b-[35px] shadow-[0_4px_25px_rgba(0,0,0,0.04)] z-20 sticky top-0">
        
        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 bg-green-600 rounded-full flex items-center justify-center text-white text-[12px] shadow-sm">🌲</div>
            <span class="font-black text-green-700 tracking-widest text-[11px] uppercase">Kafe Embun Pinus</span>
        </div>

        <div class="flex justify-between items-center mb-5">
          <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight leading-none" id="header-greeting">Halo, Tamu!</h1>
            <p class="text-gray-400 text-sm font-medium mt-1">Mau pesan apa hari ini?</p>
          </div>
          <div class="w-14 h-14 rounded-full border-[3px] border-green-100 shadow-sm flex items-center justify-center bg-green-50 text-2xl font-black text-green-600" id="header-avatar">?</div>
        </div>
        <div class="flex gap-3 items-center">
          <div class="flex-1 bg-gray-100 rounded-2xl flex items-center px-4 py-3 focus-within:ring-2 focus-within:ring-green-200 focus-within:bg-white transition-all shadow-inner">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="searchInput" onkeyup="filterMenus()" placeholder="Cari makanan favoritmu..." class="bg-transparent border-none outline-none w-full ml-3 text-sm text-gray-800 placeholder-gray-400 font-bold"/>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto px-4 pt-4 pb-32 no-scrollbar">
        <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2 mb-6 px-2" id="categoryContainer">
            <button onclick="setCategory('Semua')" class="cat-btn flex-shrink-0 bg-gray-800 text-white shadow-lg shadow-gray-800/30 px-6 py-2.5 rounded-full font-bold text-sm transition-all transform active:scale-95">🍽️ Semua</button>
            <button onclick="setCategory('Makanan')" class="cat-btn flex-shrink-0 bg-white text-gray-500 border border-gray-200 px-6 py-2.5 rounded-full font-bold text-sm transition-all transform active:scale-95">🍔 Makanan</button>
            <button onclick="setCategory('Minuman')" class="cat-btn flex-shrink-0 bg-white text-gray-500 border border-gray-200 px-6 py-2.5 rounded-full font-bold text-sm transition-all transform active:scale-95">🍹 Minuman</button>
            <button onclick="setCategory('Cemilan')" class="cat-btn flex-shrink-0 bg-white text-gray-500 border border-gray-200 px-6 py-2.5 rounded-full font-bold text-sm transition-all transform active:scale-95">🍟 Cemilan</button>
            <button onclick="setCategory('Dessert')" class="cat-btn flex-shrink-0 bg-white text-gray-500 border border-gray-200 px-6 py-2.5 rounded-full font-bold text-sm transition-all transform active:scale-95">🍰 Dessert</button>
        </div>

        <div id="waiting-info" class="hidden mx-2 mb-6 bg-gradient-to-r from-blue-500 to-blue-400 p-5 rounded-[25px] flex items-center justify-between shadow-lg shadow-blue-500/30 animate-pulse text-white">
            <div>
                <h4 class="font-black text-lg leading-tight">Pesanan Diproses! 👨‍🍳</h4>
                <p class="text-xs font-medium text-blue-100 mt-1">Tunggu notifikasi saat pesanan siap.</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-2xl">⏳</div>
        </div>

        <div class="grid grid-cols-2 gap-4 px-2" id="menuGrid">
          @foreach($menus as $menu)
            <div class="menu-card bg-white p-3.5 rounded-[24px] shadow-sm border border-gray-100 relative flex flex-col group active:bg-gray-50 transition-colors" data-category="{{ strtolower($menu->category) }}" data-name="{{ strtolower($menu->name) }}">
              <div class="relative w-full h-32 rounded-[16px] overflow-hidden mb-3 bg-gray-100 shadow-inner">
                <img src="{{ str_starts_with($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover" />
              </div>
              <h3 class="font-bold text-gray-800 text-sm leading-tight pr-8 line-clamp-2 min-h-[40px]">{{ $menu->name }}</h3>
              <p class="text-green-600 font-black text-[15px] mt-1 mb-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
              
              <button onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})" class="absolute bottom-3 right-3 px-3 py-1.5 rounded-full flex items-center justify-center text-white font-bold text-[11px] uppercase tracking-wider transition-transform active:scale-75 shadow-md bg-green-600">
                Add <span class="text-sm font-black ml-1 leading-none">+</span>
              </button>
            </div>
          @endforeach
        </div>
        <div id="no-menu-msg" class="hidden text-center py-10">
            <span class="text-4xl">🔍</span>
            <p class="text-gray-400 font-bold mt-2">Menu tidak ditemukan.</p>
        </div>
      </main>

     <div id="floating-cart" class="hidden absolute bottom-4 left-4 right-4 z-20 pb-safe transition-transform duration-300 transform translate-y-20">
        <button onclick="toggleCart(true)" class="w-full bg-green-600 hover:bg-green-700 text-white rounded-2xl p-4 shadow-[0_10px_25px_rgba(22,163,74,0.4)] flex justify-between items-center transition-all active:scale-95">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span id="cart-badge-float" class="absolute -top-2 -right-2 bg-yellow-400 text-yellow-900 w-5 h-5 rounded-full text-[10px] flex items-center justify-center font-black shadow-sm border-2 border-green-600">0</span>
                </div>
                <div class="text-left">
                    <p class="text-[10px] uppercase tracking-widest font-bold text-green-200">Total Pesanan</p>
                    <p id="cart-total-float" class="font-black text-lg leading-tight">Rp 0</p>
                </div>
            </div>
            <div class="flex items-center gap-2 font-black text-lg">
                Checkout
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </button>
      </div>

      <div id="modal-backdrop" class="hidden absolute inset-0 bg-gray-900/60 backdrop-blur-sm z-30 transition-opacity" onclick="closeAllModals()"></div>

      <div id="cart-sheet" class="bottom-sheet absolute bottom-0 left-0 right-0 bg-white rounded-t-[35px] p-6 pb-safe z-40 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] flex flex-col max-h-[85dvh]">
        <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-6"></div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-black text-gray-800">Keranjang Saya</h2>
            <button onclick="toggleCart(false)" class="bg-gray-100 hover:bg-gray-200 w-8 h-8 rounded-full font-bold text-gray-500 transition-colors">✕</button>
        </div>
        <div id="cart-items" class="flex-1 overflow-y-auto space-y-4 mb-6 no-scrollbar pr-2"></div>
        <div class="border-t border-gray-100 pt-5">
            <div class="flex justify-between items-end mb-4">
                <span class="text-gray-500 font-bold text-sm">Total Pembayaran</span>
                <span id="cart-total" class="text-2xl font-black text-green-600">Rp 0</span>
            </div>
            <button onclick="showPaymentModal()" id="btn-checkout" class="w-full bg-gray-900 text-white font-black text-lg py-4 rounded-2xl shadow-lg shadow-gray-900/30 active:scale-95 transition-transform flex items-center justify-center gap-2">
                Pilih Pembayaran ➔
            </button>
        </div>
      </div>

      <div id="payment-sheet" class="bottom-sheet absolute bottom-0 left-0 right-0 bg-white rounded-t-[35px] p-6 pb-safe z-40 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] flex flex-col max-h-[90dvh]">
        <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-6"></div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-black text-gray-800">Bayar Tagihan</h2>
            <button onclick="hidePaymentModal()" class="bg-gray-100 hover:bg-gray-200 w-8 h-8 rounded-full font-bold text-gray-500 transition-colors">✕</button>
        </div>
        <div class="bg-green-50 border border-green-100 p-4 rounded-2xl mb-5 text-center shadow-inner">
            <p class="text-green-700/80 text-xs font-bold uppercase tracking-widest mb-1">Total Tagihan <span id="pay-name"></span></p>
            <h1 id="pay-amount" class="text-3xl font-black text-green-600">Rp 0</h1>
        </div>
        <p class="font-bold text-gray-800 mb-3 text-sm">Pilih Metode Pembayaran:</p>
        
        <div class="space-y-3 overflow-y-auto no-scrollbar pb-4 flex-1">
            
            <label class="cursor-pointer block group">
                <div class="p-3 border-2 border-gray-100 rounded-2xl transition-all flex items-center justify-between has-[:checked]:border-green-500 has-[:checked]:bg-green-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-10 shrink-0 bg-yellow-400 rounded-lg flex items-center justify-center p-1.5 shadow-sm border border-yellow-500 text-2xl">💵</div>
                        <div>
                            <span class="font-black text-gray-800 block">Bayar Tunai</span>
                            <span class="text-[10px] text-gray-500 font-bold uppercase">Bayar langsung di Kasir</span>
                        </div>
                    </div>
                    <input type="radio" name="payment" value="Cash" class="w-6 h-6 accent-green-600 cursor-pointer" checked>
                </div>
            </label>

            <label class="cursor-pointer block group">
                <div class="p-3 border-2 border-gray-100 rounded-2xl transition-all flex items-center justify-between has-[:checked]:border-green-500 has-[:checked]:bg-green-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-10 shrink-0 bg-white rounded-lg flex items-center justify-center shadow-sm border border-gray-200">
                            <span class="text-blue-500 font-black italic text-[15px] tracking-tighter">gopay</span>
                        </div>
                        <span class="font-bold text-gray-800">GoPay</span>
                    </div>
                    <input type="radio" name="payment" value="GoPay" class="w-6 h-6 accent-green-600 cursor-pointer">
                </div>
            </label>

            <label class="cursor-pointer block group">
                <div class="p-3 border-2 border-gray-100 rounded-2xl transition-all flex items-center justify-between has-[:checked]:border-green-500 has-[:checked]:bg-green-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-10 shrink-0 bg-white rounded-lg flex items-center justify-center shadow-sm border border-gray-200">
                            <span class="text-blue-600 font-black italic text-[15px]">DANA</span>
                        </div>
                        <span class="font-bold text-gray-800">DANA</span>
                    </div>
                    <input type="radio" name="payment" value="DANA" class="w-6 h-6 accent-green-600 cursor-pointer">
                </div>
            </label>

            <label class="cursor-pointer block group">
                <div class="p-3 border-2 border-gray-100 rounded-2xl transition-all flex items-center justify-between has-[:checked]:border-green-500 has-[:checked]:bg-green-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-10 shrink-0 bg-white rounded-lg flex items-center justify-center shadow-sm border border-gray-200">
                            <span class="text-purple-600 font-black italic text-[15px]">OVO</span>
                        </div>
                        <span class="font-bold text-gray-800">OVO</span>
                    </div>
                    <input type="radio" name="payment" value="OVO" class="w-6 h-6 accent-green-600 cursor-pointer">
                </div>
            </label>

            <label class="cursor-pointer block group">
                <div class="p-3 border-2 border-gray-100 rounded-2xl transition-all flex items-center justify-between has-[:checked]:border-green-500 has-[:checked]:bg-green-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-10 shrink-0 bg-white rounded-lg flex items-center justify-center shadow-sm border border-gray-200">
                            <span class="text-blue-800 font-black italic text-[15px]">BCA</span>
                        </div>
                        <span class="font-bold text-gray-800">BCA Virtual Account</span>
                    </div>
                    <input type="radio" name="payment" value="BCA" class="w-6 h-6 accent-green-600 cursor-pointer">
                </div>
            </label>
        </div>

        <button onclick="processRealPayment()" class="w-full bg-green-600 text-white font-black text-lg py-4 rounded-2xl shadow-lg shadow-green-600/30 active:scale-95 transition-transform mt-2">
            Bayar Sekarang
        </button>
      </div>
    </div>

    <div id="pay-loading-screen" class="hidden fixed inset-0 bg-white z-[60] flex flex-col items-center justify-center text-center">
        <div class="w-20 h-20 border-4 border-gray-100 border-t-green-600 rounded-full animate-spin mb-6"></div>
        <h3 class="font-black text-2xl text-gray-800">Memproses...</h3>
    </div>

    <div id="pay-success-screen" class="hidden fixed inset-0 bg-green-500 z-[60] flex flex-col items-center justify-center text-center text-white">
        <div class="text-7xl mb-6 animate-[bounce_1s_infinite]">✅</div>
        <h3 class="font-black text-3xl mb-2">Pembayaran Berhasil!</h3>
        <p class="font-medium text-green-100 text-lg">Meneruskan ke dapur...</p>
    </div>

    <div id="pay-cashier-screen" class="hidden fixed inset-0 bg-yellow-400 z-[60] flex flex-col items-center justify-center text-center text-gray-900 px-6">
        <div class="text-7xl mb-6 animate-pulse">🚶‍♂️</div>
        <h3 class="font-black text-3xl mb-2">Silakan ke Kasir!</h3>
        <p class="font-medium text-yellow-900 text-lg mb-6">Tolong bayar tunai di kasir ya. Bilang aja dari <b class="bg-yellow-200 px-2 rounded">Meja {{ $meja }}</b>.</p>
        <div class="bg-white/30 px-6 py-3 rounded-2xl animate-pulse font-bold border border-yellow-500/50">
            Menunggu konfirmasi admin... ⏳
        </div>
    </div>

    <div id="ready-modal" class="hidden fixed inset-0 bg-green-500/90 z-[70] flex items-center justify-center p-6 text-center backdrop-blur-md">
        <div class="bg-white p-8 rounded-[35px] shadow-2xl w-full max-w-[340px] animate-[bounce_1s_infinite]">
            <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center text-5xl mx-auto mb-4 border-4 border-green-100 animate-pulse">🎉</div>
            <h1 class="text-3xl font-black text-green-600 mb-3 uppercase tracking-tighter">PESANAN SIAP!</h1>
            
            <div class="text-gray-700 font-bold leading-relaxed mb-6 space-y-2">
                <p>Halo <span id="alarm-name" class="text-gray-900 font-black text-lg"></span>,</p>
                <p>Makanannya udah matang dan siap diambil nih di area Pick-up. Selamat menikmati!</p>
                <p class="text-xs text-gray-400 mt-2">Ketuk tombol di bawah untuk mematikan notifikasi.</p>
            </div>
            
            <button onclick="closeReadyModal()" class="w-full bg-green-600 text-white font-black text-lg py-4 rounded-xl shadow-lg shadow-green-600/40 active:scale-95 transition-transform">OKE, SAYA AMBIL!</button>
        </div>
    </div>

    <audio id="alarmSound" src="https://assets.mixkit.co/active_storage/sfx/2013/2013-preview.mp3" preload="auto"></audio>

    <script>
        document.body.style.paddingBottom = "env(safe-area-inset-bottom)";
        let customerName = "";
        const tableNumber = "{{ $meja }}";
        let isWaitingForOrder = false; 
        
        let vibrationInterval;
        let audioLoopInterval; 

        window.addEventListener('beforeunload', function (e) {
            if (isWaitingForOrder) { e.preventDefault(); e.returnValue = ''; }
        });

        document.addEventListener("DOMContentLoaded", async function() {
            const savedOrderId = localStorage.getItem('activeOrderId');
            if (savedOrderId) {
                document.getElementById('welcome-modal').classList.add('hidden');
                try {
                    const res = await fetch('/cek-status/' + savedOrderId);
                    const data = await res.json();
                    startTrackingOrder(savedOrderId, data.status);
                } catch(e) {}
            }
        });

        document.getElementById('customer-name-input').addEventListener("keypress", function(event) { if (event.key === "Enter") saveCustomerName(); });

        function saveCustomerName() {
            const inputName = document.getElementById('customer-name-input').value.trim();
            if(!inputName) { document.getElementById('name-error').classList.remove('hidden'); document.getElementById('customer-name-input').classList.add('border-red-500', 'bg-red-50'); return; }
            customerName = inputName;
            document.getElementById('welcome-modal').classList.add('hidden');
            document.getElementById('header-greeting').innerHTML = `Halo, <br><span class="text-green-600">${customerName}</span>!`;
            document.getElementById('header-avatar').textContent = customerName.charAt(0).toUpperCase();
            document.getElementById('pay-name').textContent = "Kak " + customerName;
            document.getElementById('alarm-name').textContent = customerName;
        }

        let activeCategory = 'Semua';
        function setCategory(cat) {
            activeCategory = cat;
            document.querySelectorAll('.cat-btn').forEach(btn => {
                if(btn.textContent.includes(cat)) { btn.className = "cat-btn flex-shrink-0 bg-gray-800 text-white shadow-lg shadow-gray-800/30 px-6 py-2.5 rounded-full font-bold text-sm transition-all transform active:scale-95"; } 
                else { btn.className = "cat-btn flex-shrink-0 bg-white text-gray-500 border border-gray-200 px-6 py-2.5 rounded-full font-bold text-sm transition-all transform active:scale-95 hover:border-gray-300"; }
            }); filterMenus();
        }

        function filterMenus() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            let visibleCount = 0;
            
            document.querySelectorAll('.menu-card').forEach(card => {
                const matchSearch = card.getAttribute('data-name').includes(searchText);
                const cardCategory = card.getAttribute('data-category').toLowerCase();
                const matchCategory = (activeCategory === 'Semua') || (cardCategory === activeCategory.toLowerCase());
                
                if (matchSearch && matchCategory) { 
                    card.style.display = 'flex'; 
                    visibleCount++; 
                } else { 
                    card.style.display = 'none'; 
                }
            });
            
            if(visibleCount === 0) {
                document.getElementById('no-menu-msg').classList.remove('hidden'); 
            } else {
                document.getElementById('no-menu-msg').classList.add('hidden');
            }
        }

        let cart = [];
        function addToCart(id, name, price) {
            if(!tableNumber) return alert("⚠️ Harap Scan QR Code!");
            let item = cart.find(i => i.id === id);
            if(item) item.quantity++; else cart.push({ id, name, price, quantity: 1 });
            updateCartUI();
        }

        function removeFromCart(id) {
            let itemIndex = cart.findIndex(i => i.id === id);
            if(itemIndex > -1) { if(cart[itemIndex].quantity > 1) cart[itemIndex].quantity--; else cart.splice(itemIndex, 1); }
            updateCartUI();
            if(cart.length === 0) toggleCart(false);
        }

        function toggleCart(show) {
            if(show && cart.length > 0) {
                document.getElementById('modal-backdrop').classList.remove('hidden');
                setTimeout(() => document.getElementById('cart-sheet').classList.add('show'), 10);
            } else {
                document.getElementById('cart-sheet').classList.remove('show');
                setTimeout(() => document.getElementById('modal-backdrop').classList.add('hidden'), 300);
            }
        }

        function closeAllModals() {
            document.getElementById('cart-sheet').classList.remove('show');
            document.getElementById('payment-sheet').classList.remove('show');
            setTimeout(() => document.getElementById('modal-backdrop').classList.add('hidden'), 300);
        }

       function updateCartUI() {
            const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
            
            const floatingCart = document.getElementById('floating-cart');
            const badgeFloat = document.getElementById('cart-badge-float');
            
            if(totalQty > 0) { 
                badgeFloat.textContent = totalQty;
                floatingCart.classList.remove('hidden');
                setTimeout(() => { floatingCart.classList.remove('translate-y-20'); }, 10);
            } else { 
                floatingCart.classList.add('translate-y-20');
                setTimeout(() => { floatingCart.classList.add('hidden'); }, 300);
            }
            
            const list = document.getElementById('cart-items'); list.innerHTML = '';
            let totalPrice = 0;

            cart.forEach(item => {
                totalPrice += item.price * item.quantity;
                list.innerHTML += `<div class="flex justify-between items-center bg-gray-50 p-3 rounded-2xl border border-gray-100"><div class="flex-1"><p class="font-bold text-gray-800 text-sm leading-tight">${item.name}</p><p class="text-green-600 font-black text-sm mt-0.5">Rp ${item.price.toLocaleString('id-ID')}</p></div><div class="flex items-center gap-3 bg-white rounded-xl p-1 border border-gray-200 shadow-sm ml-2"><button onclick="removeFromCart(${item.id})" class="w-8 h-8 flex items-center justify-center font-bold text-gray-600 hover:text-red-500 active:scale-90 transition-all">-</button><span class="font-black text-sm w-4 text-center">${item.quantity}</span><button onclick="addToCart(${item.id}, '${item.name}', ${item.price})" class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center font-bold text-white active:scale-90 transition-all">+</button></div></div>`;
            });
            
            document.getElementById('cart-total').textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
            document.getElementById('pay-amount').textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
            document.getElementById('cart-total-float').textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
            
            if(cart.length === 0) closeAllModals();
        }

        function showPaymentModal() { document.getElementById('cart-sheet').classList.remove('show'); setTimeout(() => document.getElementById('payment-sheet').classList.add('show'), 200); }
        function hidePaymentModal() { document.getElementById('payment-sheet').classList.remove('show'); setTimeout(() => toggleCart(true), 200); }

        async function processRealPayment() {
            document.getElementById('payment-sheet').classList.remove('show');
            const loadingScreen = document.getElementById('pay-loading-screen');
            loadingScreen.classList.remove('hidden');
            
            const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const selectedPayment = document.querySelector('input[name="payment"]:checked').value;

            const alarm = document.getElementById('alarmSound');
            alarm.volume = 0;
            alarm.play().then(() => {
                alarm.pause();
                alarm.currentTime = 0;
            }).catch(e => console.log("Audio unlock:", e));

            setTimeout(async () => {
                try {
                    const response = await fetch('/checkout', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ table_number: 'Meja ' + tableNumber, customer_name: customerName, payment_method: selectedPayment, items: cart, total_price: totalPrice })
                    });
                    const data = await response.json();
                    
                    if(response.ok) {
                        loadingScreen.classList.add('hidden');
                        localStorage.setItem('activeOrderId', data.order_id);
                        closeAllModals(); cart = []; updateCartUI();
                        
                        if(data.status === 'belum_dibayar') {
                            document.getElementById('pay-cashier-screen').classList.remove('hidden');
                            startTrackingOrder(data.order_id, 'belum_dibayar');
                        } else {
                            const successScreen = document.getElementById('pay-success-screen');
                            successScreen.classList.remove('hidden');
                            setTimeout(() => {
                                successScreen.classList.add('hidden');
                                document.getElementById('waiting-info').classList.remove('hidden');
                                startTrackingOrder(data.order_id, 'pending');
                            }, 2000);
                        }
                    } else { loadingScreen.classList.add('hidden'); alert("❌ Pembayaran gagal."); }
                } catch(e) { loadingScreen.classList.add('hidden'); alert("Koneksi gagal."); } 
            }, 1000); 
        }

        function startTrackingOrder(orderId, statusAwal) {
            isWaitingForOrder = true; 

            if(statusAwal === 'belum_dibayar') {
                document.getElementById('pay-cashier-screen').classList.remove('hidden');
            } else if (statusAwal === 'pending') {
                document.getElementById('waiting-info').classList.remove('hidden');
            }

            const interval = setInterval(async () => {
                try {
                    const res = await fetch('/cek-status/' + orderId);
                    const data = await res.json();
                    
                    if(data.status === 'pending' && !document.getElementById('pay-cashier-screen').classList.contains('hidden')) {
                        document.getElementById('pay-cashier-screen').classList.add('hidden');
                        document.getElementById('waiting-info').classList.remove('hidden');
                    }
                    
                    if(data.status === 'selesai') { 
                        clearInterval(interval); 
                        document.getElementById('pay-cashier-screen').classList.add('hidden');
                        triggerAlarm(); 
                    }
                } catch(e) {}
            }, 3000);
        }

        function triggerAlarm() {
            isWaitingForOrder = false; 
            document.getElementById('waiting-info').classList.add('hidden');
            document.getElementById('ready-modal').classList.remove('hidden');
            
            const alarm = document.getElementById('alarmSound');
            alarm.volume = 1.0; 
            alarm.currentTime = 0; 
            alarm.play().catch(e=>{});

            audioLoopInterval = setInterval(() => {
                alarm.currentTime = 0; 
                alarm.play().catch(e=>{});
            }, 2000); 

            if (navigator.vibrate) {
                navigator.vibrate([600, 400]); 
                vibrationInterval = setInterval(() => {
                    navigator.vibrate([600, 400]);
                }, 2000); 
            }
        }

        function closeReadyModal() {
            document.getElementById('ready-modal').classList.add('hidden');
            
            clearInterval(audioLoopInterval);
            
            const alarm = document.getElementById('alarmSound');
            alarm.pause();
            alarm.currentTime = 0;
            
            if (navigator.vibrate) {
                clearInterval(vibrationInterval);
                navigator.vibrate(0); 
            }

            localStorage.removeItem('activeOrderId');
            window.location.reload(); 
        }
    </script>
</body>
</html>