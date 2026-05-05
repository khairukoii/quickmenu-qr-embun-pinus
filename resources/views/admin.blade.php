<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Embun Pinus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .hover-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .hover-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        
        .btn-shiny { position: relative; overflow: hidden; }
        .btn-shiny::after {
            content: ''; position: absolute; top: -50%; left: -60%; width: 20%; height: 200%;
            background: rgba(255,255,255,0.3); transform: rotate(30deg); transition: transform 0.6s ease-in-out;
        }
        .btn-shiny:hover::after { transform: translateX(400%) rotate(30deg); }

        /* CSS Khusus Untuk Print QR Code */
        @media print {
            aside, .no-print, .hover-card:not(#tab-qr), #tab-dashboard, #tab-menu, #tab-riwayat { display: none !important; }
            main { margin-left: 0 !important; padding: 0 !important; background: white !important;}
            body { background-color: white !important; }
            #tab-qr { display: block !important; border: none !important; box-shadow: none !important; padding: 0 !important; }
            #qr-container { grid-template-columns: repeat(4, 1fr) !important; gap: 20px !important; }
            .qr-card { border: 2px dashed #ccc !important; page-break-inside: avoid; margin-bottom: 20px; }
        }
    </style>
</head>
<body class="bg-[#F3F4F6] font-sans flex min-h-screen text-gray-800">

    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col fixed h-full z-20 shadow-[4px_0_24px_rgba(0,0,0,0.02)] no-print">
        <div class="p-6 border-b border-gray-50 flex items-center gap-3">
            <div class="w-8 h-8 bg-green-700 rounded-lg flex items-center justify-center text-white font-bold shadow-md shadow-green-700/30">🌲</div>
            <h1 class="text-xl font-black">Embun<span class="text-green-700">Pinus</span></h1>
        </div>
        
        <nav class="p-4 space-y-2 flex-1 mt-2">
            <button onclick="switchTab('dashboard')" id="btn-dashboard" class="sidebar-btn w-full flex items-center gap-3 text-left px-4 py-3 rounded-xl font-bold bg-green-50 text-green-700 transition-all hover:translate-x-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </button>
            <button onclick="switchTab('menu')" id="btn-menu" class="sidebar-btn w-full flex items-center gap-3 text-left px-4 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-all hover:translate-x-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                Kelola Menu
            </button>
            <button onclick="switchTab('riwayat')" id="btn-riwayat" class="sidebar-btn w-full flex items-center gap-3 text-left px-4 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-all hover:translate-x-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Riwayat Order
            </button>
            
            <button onclick="switchTab('qr')" id="btn-qr" class="sidebar-btn w-full flex items-center gap-3 text-left px-4 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-all hover:translate-x-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                QR Code Meja
            </button>

            <hr class="my-4 border-gray-100">
            <a href="{{ url('/dapur') }}" target="_blank" class="btn-shiny w-full flex items-center gap-3 text-left px-4 py-3 rounded-xl font-bold text-white bg-gray-900 hover:bg-black shadow-lg transition-all hover:-translate-y-1">
                👨‍🍳 Masuk Dapur
            </a>
        </nav>

        <div class="p-4 border-t border-gray-50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-center bg-red-50 text-red-500 px-4 py-3 rounded-xl font-bold hover:bg-red-500 hover:text-white transition-colors duration-300">Logout</button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-8 relative">
        
        <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover-card no-print">
            <div>
                <h2 class="text-2xl font-black text-gray-800" id="page-title">Ringkasan Penghasilan</h2>
                <p class="text-sm text-gray-400 font-medium mt-1">Pantau perkembangan bisnismu hari ini.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="font-bold text-gray-800 text-sm">Admin Koii</p>
                    <p class="text-xs text-green-600 font-bold tracking-widest uppercase">Owner</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-2xl shadow-inner border border-green-100">👋</div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 font-bold flex items-center gap-3 border border-green-200 shadow-sm animate-pulse no-print">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <div id="tab-dashboard" class="tab-content">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-700 to-green-500 rounded-3xl p-6 text-white shadow-lg shadow-green-600/30 relative overflow-hidden hover-card group">
                    <div class="relative z-10">
                        <p class="text-white/80 font-bold text-xs uppercase tracking-wider mb-1">Total Pendapatan</p>
                        <h2 class="text-3xl font-black truncate">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
                    </div>
                    <svg class="absolute -right-4 -bottom-4 w-28 h-28 text-white opacity-10 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 hover-card group flex flex-col justify-center">
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-wider mb-1">Total Pesanan Selesai</p>
                    <h2 class="text-4xl font-black text-gray-800">{{ $totalPesanan }} <span class="text-lg text-gray-400">Porsi</span></h2>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 hover-card group flex flex-col justify-center">
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-wider mb-1">Antrean Dapur</p>
                    <h2 class="text-4xl font-black {{ $pesananPending > 0 ? 'text-red-500' : 'text-green-500' }}">{{ $pesananPending }} <span class="text-lg text-gray-400">Antre</span></h2>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover-card">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Grafik Mingguan</h3>
                    <div class="h-64 w-full relative"><canvas id="mingguanChart"></canvas></div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover-card">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-orange-500"></span> Grafik Bulanan</h3>
                    <div class="h-64 w-full relative"><canvas id="bulananChart"></canvas></div>
                </div>
            </div>
        </div>

        <div id="tab-menu" class="tab-content hidden grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm h-fit hover-card">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><span class="text-xl">🍔</span> Tambah Menu Baru</h3>
                <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="text" name="name" required placeholder="Nama Menu" class="w-full bg-[#F5F6F8] border border-transparent focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-500/10 rounded-xl px-4 py-3 outline-none transition-all font-bold">
                    <input type="number" name="price" required placeholder="Harga (Rp)" class="w-full bg-[#F5F6F8] border border-transparent focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-500/10 rounded-xl px-4 py-3 outline-none transition-all font-bold">
                    <select name="category" required class="w-full bg-[#F5F6F8] border border-transparent focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-500/10 rounded-xl px-4 py-3 outline-none transition-all font-bold text-gray-600">
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Cemilan">Cemilan</option>
                        <option value="Dessert">Dessert</option>
                    </select>
                    <div class="p-4 bg-[#F5F6F8] border border-dashed border-gray-300 rounded-xl hover:border-green-500 transition-colors">
                        <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Upload Foto Menu</label>
                        <input type="file" name="image" accept="image/*" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-600 hover:file:bg-green-100 cursor-pointer">
                    </div>
                    <button type="submit" class="btn-shiny w-full bg-green-600 text-white font-black text-lg py-3 rounded-xl hover:bg-green-700 transition-colors shadow-lg shadow-green-600/30 mt-2">+ Simpan Menu</button>
                </form>
            </div>
            
            <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover-card flex flex-col">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h3 class="font-bold text-gray-800">Daftar Menu Tersedia</h3>
                    
                    <div class="relative w-full sm:w-64">
                        <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
                        <input type="text" id="searchAdminMenu" onkeyup="filterAdminMenu()" placeholder="Cari nama atau jenis..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2 pl-10 pr-4 text-sm font-bold text-gray-700 focus:border-green-500 focus:bg-white outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($menus as $menu)
                        <div class="admin-menu-card border {{ $menu->is_available ? 'border-gray-100 hover:border-green-200' : 'border-red-100 bg-red-50/30' }} rounded-2xl p-3 flex gap-3 items-center relative transition-all group" data-name="{{ strtolower($menu->name) }}" data-category="{{ strtolower($menu->category) }}">
                            
                            <img src="{{ str_starts_with($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image) }}" class="w-16 h-16 rounded-xl object-cover bg-gray-100 shadow-sm {{ !$menu->is_available ? 'grayscale opacity-50' : '' }}">
                            
                            <div class="flex-1 pr-20">
                                <h4 class="font-bold text-gray-800 text-sm leading-tight {{ !$menu->is_available ? 'line-through text-gray-400' : '' }}">{{ $menu->name }}</h4>
                                <p class="text-[10px] text-gray-600 font-bold bg-gray-100 inline-block px-2 py-0.5 rounded-md mt-1 mb-1">{{ $menu->category }}</p>
                                <p class="text-gray-800 font-black text-sm">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                @if(!$menu->is_available) <span class="text-[10px] font-black text-red-500 uppercase ml-1">Habis</span> @endif
                            </div>

                            <div class="absolute right-3 flex flex-col gap-2">
                                <button onclick="openEditModal({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }}, '{{ $menu->category }}', {{ $menu->is_available ? 'true' : 'false' }})" class="text-blue-500 hover:text-white hover:bg-blue-500 bg-blue-50 w-8 h-8 rounded-lg flex items-center justify-center transition-transform active:scale-90 shadow-sm" title="Edit Menu">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                
                                <form action="{{ route('menu.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu {{ $menu->name }}?');">
                                    @csrf
                                    <button type="submit" class="text-red-400 hover:text-white hover:bg-red-500 bg-red-50 w-8 h-8 rounded-lg flex items-center justify-center transition-transform active:scale-90 shadow-sm" title="Hapus Menu">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="tab-riwayat" class="tab-content hidden bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover-card">
            <h3 class="font-bold text-gray-800 mb-6">Riwayat Order Keseluruhan</h3>
            <div class="overflow-hidden rounded-2xl border border-gray-100 shadow-inner">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="py-4 px-6 text-xs text-gray-500 font-bold uppercase tracking-wider">Meja</th>
                            <th class="py-4 px-6 text-xs text-gray-500 font-bold uppercase tracking-wider">Tanggal & Waktu</th>
                            <th class="py-4 px-6 text-xs text-gray-500 font-bold uppercase tracking-wider">Detail Pesanan</th>
                            <th class="py-4 px-6 text-xs text-gray-500 font-bold uppercase tracking-wider">Total Harga</th>
                            <th class="py-4 px-6 text-xs text-gray-500 font-bold uppercase tracking-wider text-center">Status</th>
                            <th class="py-4 px-6 text-xs text-gray-500 font-bold uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @foreach($riwayatPesanan as $order)
                            <tr class="hover:bg-green-50/50 transition-colors group cursor-default">
                                <td class="py-4 px-6 group-hover:text-green-600 transition-colors">
                                    <div class="font-black text-gray-800 text-lg">{{ $order->table_number }}</div>
                                    <div class="text-[10px] font-black text-green-600 uppercase tracking-tighter italic">A/n: {{ $order->customer_name ?? 'Tamu' }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-sm font-bold text-gray-700">{{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d M Y') }}</p>
                                    <p class="text-xs text-gray-400 font-medium">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }} WIB</p>
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-600 font-medium">
                                    <div class="flex flex-wrap gap-1">
                                    @foreach(json_decode($order->items) as $item) 
                                        <span class="inline-block bg-gray-100 border border-gray-200 px-2 py-1 rounded-md text-xs">{{ $item->quantity }}x {{ $item->name }}</span>
                                    @endforeach
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-black text-gray-800">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}<br>
                                    <span class="text-[10px] bg-gray-100 px-2 py-0.5 rounded font-bold text-gray-600 uppercase">{{ $order->payment_method }}</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($order->status == 'belum_dibayar')
                                        <span class="px-3 py-1.5 rounded-full text-[10px] font-extrabold uppercase tracking-widest shadow-sm bg-red-100 text-red-600 border border-red-200 animate-pulse">Menunggu Bayar</span>
                                    @elseif($order->status == 'pending')
                                        <span class="px-3 py-1.5 rounded-full text-[10px] font-extrabold uppercase tracking-widest shadow-sm bg-yellow-100 text-yellow-700 border border-yellow-200">Di Dapur</span>
                                    @else
                                        <span class="px-3 py-1.5 rounded-full text-[10px] font-extrabold uppercase tracking-widest shadow-sm bg-green-100 text-green-700 border border-green-200">Selesai</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center flex gap-2 justify-center">
                                    @if($order->status == 'belum_dibayar')
                                        <form action="{{ route('order.konfirmasi_bayar', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-xs font-bold transition-transform active:scale-95 shadow-md">
                                                💰 Terima Uang
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('order.struk', $order->id) }}" target="_blank" class="inline-flex items-center gap-1 bg-gray-800 hover:bg-black text-white px-3 py-2 rounded-lg text-xs font-bold transition-transform active:scale-95 shadow-md">
                                        🖨️ Cetak
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab-qr" class="tab-content hidden bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover-card">
            <div class="flex justify-between items-center mb-6 no-print">
                <h3 class="font-bold text-gray-800 text-xl">Generator QR Code Meja</h3>
                <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg flex items-center gap-2 transition-transform active:scale-95">
                    🖨️ Cetak Semua QR
                </button>
            </div>

            <div class="bg-[#F5F6F8] p-5 rounded-2xl mb-8 flex flex-wrap gap-4 items-end no-print border border-gray-200">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Alamat IP Laptop</label>
                    <input type="text" id="ip-address" value="http://10.137.26.37:8000" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 outline-none font-medium text-gray-700 bg-white shadow-inner">
                </div>
                <div class="w-32">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Jumlah Meja</label>
                    <input type="number" id="table-count" value="10" min="1" max="50" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 outline-none font-black text-center text-gray-800 bg-white shadow-inner">
                </div>
                <button onclick="generateQR()" class="bg-gray-800 hover:bg-black text-white px-8 py-3 rounded-xl font-bold shadow-md transition-transform active:scale-95 h-[48px]">
                    Buat QR
                </button>
            </div>

            <div class="hidden print:block text-center mb-8">
                <h1 class="text-3xl font-black">EMBUN PINUS COFFEE</h1>
                <p class="text-lg font-bold text-gray-600">Scan QR Code di bawah ini untuk memesan makanan.</p>
            </div>

            <div id="qr-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 text-center"></div>
        </div>

        <div id="edit-modal" class="hidden fixed inset-0 bg-gray-900/80 z-[100] flex items-center justify-center backdrop-blur-sm px-4 transition-opacity">
            <div class="bg-white rounded-[30px] p-8 w-full max-w-md shadow-2xl relative transform transition-all">
                <button onclick="closeEditModal()" class="absolute top-4 right-4 w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-500 transition-colors">✕</button>
                
                <h3 class="text-2xl font-black text-gray-800 mb-6 flex items-center gap-2"><span class="text-blue-500">✏️</span> Edit Menu</h3>
                
                <form id="edit-form" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wide">Nama Menu</label>
                        <input type="text" id="edit-name" name="name" required class="w-full bg-[#F5F6F8] border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl px-4 py-3 outline-none transition-all font-bold text-gray-700">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wide">Harga (Rp)</label>
                        <input type="number" id="edit-price" name="price" required class="w-full bg-[#F5F6F8] border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl px-4 py-3 outline-none transition-all font-bold text-gray-700">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-wide">Kategori</label>
                        <select id="edit-category" name="category" required class="w-full bg-[#F5F6F8] border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl px-4 py-3 outline-none transition-all font-bold text-gray-700">
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Cemilan">Cemilan</option>
                            <option value="Dessert">Dessert</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-[#F5F6F8] rounded-xl border border-gray-200">
                        <div>
                            <p class="font-bold text-gray-700">Status Stok</p>
                            <p class="text-xs text-gray-500">Apakah menu ini tersedia?</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="edit-status" name="is_available" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        </label>
                    </div>
                    
                    <div class="p-4 bg-[#F5F6F8] border border-dashed border-gray-300 rounded-xl hover:border-blue-500 transition-colors">
                        <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Ganti Foto (Opsional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white font-black text-lg py-4 rounded-xl shadow-lg shadow-blue-600/30 active:scale-95 transition-transform mt-4 hover:bg-blue-700">Simpan Perubahan</button>
                </form>
            </div>
        </div>

    </main>

    <script>
        // FITUR PENCARIAN MENU DI ADMIN (Nama & Kategori)
        function filterAdminMenu() {
            const text = document.getElementById('searchAdminMenu').value.toLowerCase();
            document.querySelectorAll('.admin-menu-card').forEach(card => {
                const name = card.getAttribute('data-name') || '';
                const category = card.getAttribute('data-category') || '';
                
                if(name.includes(text) || category.includes(text)) { 
                    card.style.display = 'flex'; 
                } else { 
                    card.style.display = 'none'; 
                }
            });
        }

        // LOGIKA PERPINDAHAN TAB DENGAN LOCAL STORAGE
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            
            document.querySelectorAll('.sidebar-btn').forEach(btn => {
                btn.classList.remove('bg-green-50', 'text-green-700');
                btn.classList.add('text-gray-500');
            });
            let activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.classList.remove('text-gray-500');
            activeBtn.classList.add('bg-green-50', 'text-green-700');

            const titles = { 
                dashboard: 'Ringkasan Penghasilan', 
                menu: 'Kelola Menu Resto', 
                riwayat: 'Riwayat Pesanan',
                qr: 'Cetak QR Code Meja' 
            };
            document.getElementById('page-title').textContent = titles[tabId];

            localStorage.setItem('activeAdminTab', tabId);
        }

        // Baca memori saat halaman dimuat ulang
        document.addEventListener("DOMContentLoaded", function() {
            let savedTab = localStorage.getItem('activeAdminTab');
            if(savedTab) { switchTab(savedTab); }
        });

        // LOGIKA MODAL EDIT MENU
        function openEditModal(id, name, price, category, isAvailable) {
            document.getElementById('edit-modal').classList.remove('hidden');
            
            // Set action form ke ID menu yang diklik
            document.getElementById('edit-form').action = '/menu/' + id;
            
            // Isi data lama
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-price').value = price;
            document.getElementById('edit-category').value = category;
            
            // Atur status saklar Tersedia / Habis
            document.getElementById('edit-status').checked = isAvailable;
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        // Data Grafik
        const mingguanLabels = @json($mingguanLabels); const mingguanData = @json($mingguanData);
        const bulananLabels = @json($bulananLabels); const bulananData = @json($bulananData);

        Chart.defaults.font.family = "'Inter', 'sans-serif'";
        Chart.defaults.color = '#9ca3af';

        // Grafik Mingguan
        new Chart(document.getElementById('mingguanChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: mingguanLabels,
                datasets: [{ 
                    label: 'Pendapatan (Rp)', data: mingguanData, 
                    borderColor: '#10b981', backgroundColor: 'rgba(16, 185, 129, 0.1)', 
                    fill: true, tension: 0.4, borderWidth: 3, pointBackgroundColor: '#ffffff', pointBorderColor: '#10b981', pointBorderWidth: 2, pointRadius: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { borderDash: [4, 4] } }, x: { grid: { display: false } } } }
        });

        // Grafik Bulanan
        new Chart(document.getElementById('bulananChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: bulananLabels,
                datasets: [{ 
                    label: 'Pendapatan (Rp)', data: bulananData, 
                    backgroundColor: '#f97316', borderRadius: 6 
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { borderDash: [4, 4] } }, x: { grid: { display: false } } } }
        });

        function generateQR() {
            const ipInput = document.getElementById('ip-address').value;
            const ip = ipInput.replace(/\/$/, ""); 
            const count = document.getElementById('table-count').value;
            const container = document.getElementById('qr-container');
            
            container.innerHTML = '';

            for (let i = 1; i <= count; i++) {
                const tableNum = i.toString().padStart(2, '0');
                const url = `${ip}/?meja=${tableNum}`;
                const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(url)}`;

                container.innerHTML += `
                    <div class="qr-card border border-gray-200 p-4 rounded-2xl bg-white shadow-sm flex flex-col items-center">
                        <h4 class="font-black text-gray-800 text-xl mb-1">MEJA ${tableNum}</h4>
                        <p class="text-[10px] text-gray-400 mb-3 uppercase tracking-wider font-bold">Embun Pinus</p>
                        <img src="${qrUrl}" alt="QR Meja ${tableNum}" class="w-full h-auto aspect-square rounded-xl mb-3 border border-gray-100 p-2 shadow-inner">
                        <p class="text-[8px] text-gray-400 break-all bg-gray-50 px-2 py-1 rounded w-full border border-gray-100">${url}</p>
                    </div>
                `;
            }
        }
        
        setTimeout(generateQR, 500);
    </script>
</body>
</html>