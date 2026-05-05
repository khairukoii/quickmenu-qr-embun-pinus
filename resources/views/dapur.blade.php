<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="15"> <title>Layar Dapur (KDS) - QuickMenu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; border-radius: 8px; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 8px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        
        /* Animasi Kritis untuk Pesanan yang Terlalu Lama */
        @keyframes urgent-pulse {
            0%, 100% { border-color: rgba(239, 68, 68, 1); box-shadow: 0 0 15px rgba(239, 68, 68, 0.5); }
            50% { border-color: rgba(239, 68, 68, 0.3); box-shadow: 0 0 5px rgba(239, 68, 68, 0.1); }
        }
        .urgent-card { animation: urgent-pulse 1.5s ease-in-out infinite; }
        
        /* Background pattern ala dashboard mesin */
        .bg-pattern { background-image: radial-gradient(#374151 1px, transparent 1px); background-size: 20px 20px; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 font-sans h-screen flex flex-col overflow-hidden">

    <header class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center shadow-lg relative z-20">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-600 rounded-xl flex items-center justify-center text-2xl shadow-lg shadow-orange-600/30">👨‍🍳</div>
            <div>
                <h1 class="text-2xl font-black tracking-widest uppercase text-white">Kitchen <span class="text-orange-500">Display</span></h1>
                <p class="text-sm text-gray-400 font-bold mt-1">Sistem Antrean Dapur Real-Time</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 text-xs font-bold text-gray-400 bg-gray-900/50 px-3 py-1.5 rounded-full border border-gray-700">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                </span>
                LIVE SYNC
            </div>

            <div class="bg-gray-900 border border-gray-700 px-6 py-2 rounded-xl text-center shadow-inner">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Waktu Dapur</p>
                <h2 id="clock" class="text-2xl font-black text-orange-500 font-mono tracking-wider">00:00:00</h2>
            </div>
            
            <a href="{{ url('/admin') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl transition-colors border border-gray-600 shadow-sm flex items-center gap-2 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar KDS
            </a>
        </div>
    </header>

    @if(session('success'))
        <div id="toast-success" class="bg-green-500 text-white text-center py-3 font-black tracking-widest text-lg shadow-lg flex items-center justify-center gap-2 transition-all duration-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <main class="flex-1 p-6 overflow-x-auto overflow-y-auto bg-gray-900 bg-pattern">
        
        <div class="flex gap-6 items-start h-full pb-4 w-max">
            @forelse($pendingOrders as $order)
                @php 
                    $items = json_decode($order->items); 
                    
                    // PERHITUNGAN WAKTU TRAFFIC LIGHT
                    $orderTime = \Carbon\Carbon::parse($order->created_at);
                    $now = \Carbon\Carbon::now();
                    $diffInMinutes = $now->diffInMinutes($orderTime);
                    
                    // Logika Warna Status:
                    $statusColor = 'bg-blue-600'; // Default: Biru (Baru)
                    $statusText = 'PESANAN BARU';
                    $statusIcon = '✨';
                    $borderClass = 'border-blue-500/50';
                    $isNew = false;
                    $isUrgent = false;

                    if ($diffInMinutes < 1) {
                        $isNew = true; // Muncul badge NEW
                    }

                    if ($diffInMinutes >= 2 && $diffInMinutes < 5) {
                        $statusColor = 'bg-yellow-500';
                        $statusText = 'SEDANG DISIAPKAN';
                        $statusIcon = '⏳';
                        $borderClass = 'border-yellow-500/50';
                    } elseif ($diffInMinutes >= 5) {
                        $statusColor = 'bg-red-600';
                        $statusText = 'WAKTU TERLEWAT!';
                        $statusIcon = '🔥';
                        $borderClass = 'border-red-600 urgent-card border-2';
                        $isUrgent = true;
                    }
                @endphp
                
                <div class="w-[340px] flex-shrink-0 bg-gray-800 rounded-2xl flex flex-col h-fit max-h-full border shadow-2xl relative {{ $borderClass }} transition-colors duration-500">
                    
                    @if($isNew)
                    <div class="absolute -top-3 -right-3 bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg border-2 border-gray-900 animate-[bounce_1s_infinite] z-30">
                        NEW!
                    </div>
                    @endif

                    <div class="{{ $statusColor }} p-4 rounded-t-2xl flex justify-between items-center shadow-inner relative overflow-hidden transition-colors duration-500">
                        <div class="relative z-10">
                            <p class="text-white/80 text-[11px] font-black uppercase tracking-widest mb-1 flex items-center gap-1">
                                <span>{{ $statusIcon }}</span> {{ $statusText }}
                            </p>
                            <h2 class="text-3xl font-black text-white drop-shadow-md">{{ $order->table_number }}</h2>
                        </div>
                        <div class="relative z-10 flex flex-col items-end">
                            <p class="text-[10px] text-white/80 font-bold uppercase tracking-widest">Order #{{ $order->id }}</p>
                            <div class="bg-black/30 px-3 py-1.5 rounded-xl text-center mt-1 border border-white/10 backdrop-blur-sm">
                                <p class="text-sm font-black text-white font-mono">{{ $order->created_at->format('H:i') }}</p>
                            </div>
                            @if($diffInMinutes > 0)
                            <p class="text-[10px] text-white font-bold mt-1 bg-black/40 px-2 py-0.5 rounded-md">
                                Menunggu: {{ $diffInMinutes }} mnt
                            </p>
                            @endif
                        </div>
                        <div class="absolute inset-0 opacity-20 bg-[repeating-linear-gradient(45deg,transparent,transparent_10px,#000_10px,#000_20px)] mix-blend-overlay"></div>
                    </div>

                    <div class="p-5 flex-1 overflow-y-auto">
                        <ul class="space-y-4">
                            @foreach($items as $item)
                                <li class="flex gap-4 items-start border-b border-gray-700/50 pb-4 last:border-0 last:pb-0">
                                    <div class="bg-gray-700 text-white font-black text-xl w-10 h-10 rounded-xl flex items-center justify-center shadow-inner border border-gray-600">
                                        {{ $item->quantity }}
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <p class="text-lg font-bold text-gray-100 leading-tight">{{ $item->name }}</p>
                                    </div>
                                    <button class="w-8 h-8 rounded-full border-2 border-gray-600 flex items-center justify-center text-gray-600 hover:border-green-500 hover:text-green-500 hover:bg-green-500/10 transition-colors" onclick="this.classList.toggle('border-green-500'); this.classList.toggle('text-green-500'); this.classList.toggle('bg-green-500/10');">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="p-4 bg-gray-800 border-t border-gray-700 rounded-b-2xl">
                        <form action="{{ url('/dapur/order/'.$order->id.'/selesai') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-500 text-white font-black text-lg py-4 rounded-xl transition-all active:scale-95 shadow-[0_0_15px_rgba(22,163,74,0.2)] flex items-center justify-center gap-2 border border-green-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                SELESAI MASAK
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 absolute inset-0 bg-gray-900/80 backdrop-blur-sm z-10">
                    <div class="relative mb-6">
                        <span class="text-8xl opacity-30 grayscale filter">🍽️</span>
                        <div class="absolute top-0 right-0 w-4 h-4 bg-green-500 rounded-full animate-ping"></div>
                    </div>
                    <h2 class="text-4xl font-black text-gray-600 mb-2 tracking-widest uppercase">Dapur Bersih</h2>
                    <p class="text-xl font-medium text-gray-500">Menunggu pesanan baru masuk dari pelanggan...</p>
                </div>
            @endforelse
        </div>
    </main>

    <script>
        // 1. Jam Digital Real-Time
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('clock').textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // 2. Menghilangkan Toast Notifikasi secara halus setelah 3 detik
        const toast = document.getElementById('toast-success');
        if (toast) {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-100%)';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        // Auto refresh sekarang dipindah ke tag <meta http-equiv="refresh" content="15"> di bagian <head> 
        // agar proses refresh lebih bersih dan tidak membebani memori browser kasir yang menyala seharian.
    </script>
</body>
</html>