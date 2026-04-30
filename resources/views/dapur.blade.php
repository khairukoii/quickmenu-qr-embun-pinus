<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layar Dapur (KDS) - QuickMenu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Desain Scrollbar untuk Dapur */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        
        /* Animasi Kedip untuk pesanan baru */
        @keyframes pulse-border {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .order-card { animation: pulse-border 2s infinite; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 font-sans h-screen flex flex-col overflow-hidden">

    <header class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center shadow-md">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-600 rounded-xl flex items-center justify-center text-2xl shadow-lg shadow-orange-600/30">👨‍🍳</div>
            <div>
                <h1 class="text-2xl font-black tracking-widest uppercase text-white">Kitchen <span class="text-orange-500">Display</span></h1>
                <p class="text-sm text-gray-400 font-bold mt-1">Sistem Antrean Dapur Real-Time</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="bg-gray-900 border border-gray-700 px-6 py-2 rounded-xl text-center shadow-inner">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Waktu Dapur</p>
                <h2 id="clock" class="text-2xl font-black text-orange-500 font-mono tracking-wider">00:00:00</h2>
            </div>
            
            <a href="{{ url('/admin') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl transition-colors border border-gray-600 shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar KDS
            </a>
        </div>
    </header>

    @if(session('success'))
        <div class="bg-green-500 text-white text-center py-2 font-black tracking-widest text-lg shadow-lg">
            ✅ {{ session('success') }}
        </div>
    @endif

    <main class="flex-1 p-6 overflow-x-auto overflow-y-auto bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]">
        
        <div class="flex gap-6 items-start h-full pb-4 w-max">
            @forelse($pendingOrders as $order)
                @php $items = json_decode($order->items); @endphp
                
                <div class="w-80 flex-shrink-0 bg-gray-800 rounded-2xl flex flex-col h-fit max-h-full border border-gray-700 shadow-2xl order-card">
                    
                    <div class="bg-red-600 p-4 rounded-t-2xl flex justify-between items-center shadow-inner relative overflow-hidden">
                        <div class="relative z-10">
                            <p class="text-red-200 text-xs font-black uppercase tracking-widest mb-1">Order #{{ $order->id }}</p>
                            <h2 class="text-3xl font-black text-white">{{ $order->table_number }}</h2>
                        </div>
                        <div class="relative z-10 bg-black/30 px-3 py-1 rounded-lg text-center">
                            <p class="text-[10px] text-red-200 font-bold uppercase">Waktu</p>
                            <p class="text-sm font-black text-white">{{ $order->created_at->format('H:i') }}</p>
                        </div>
                        <div class="absolute inset-0 opacity-20 bg-[repeating-linear-gradient(45deg,transparent,transparent_10px,#000_10px,#000_20px)]"></div>
                    </div>

                    <div class="p-5 flex-1 overflow-y-auto">
                        <ul class="space-y-4">
                            @foreach($items as $item)
                                <li class="flex gap-4 items-start border-b border-gray-700/50 pb-4 last:border-0 last:pb-0">
                                    <div class="bg-gray-700 text-orange-400 font-black text-xl w-10 h-10 rounded-lg flex items-center justify-center shadow-inner">
                                        {{ $item->quantity }}
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <p class="text-lg font-bold text-gray-100 leading-tight">{{ $item->name }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="p-4 bg-gray-850 border-t border-gray-700">
                        <form action="{{ url('/dapur/order/'.$order->id.'/selesai') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-black text-xl py-4 rounded-xl transition-transform active:scale-95 shadow-[0_0_15px_rgba(34,197,94,0.4)] flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                SIAP SAJIKAN
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 absolute inset-0">
                    <span class="text-8xl mb-6 opacity-20">🍽️</span>
                    <h2 class="text-4xl font-black text-gray-600 mb-2 tracking-widest uppercase">Dapur Kosong</h2>
                    <p class="text-xl font-medium">Belum ada pesanan masuk, silakan istirahat.</p>
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

        // 2. Auto-Refresh Halaman Setiap 15 Detik
        // Supaya koki tidak perlu pencet refresh untuk melihat pesanan baru
        setTimeout(function() {
            window.location.reload();
        }, 15000); // 15000 milidetik = 15 detik
    </script>
</body>
</html>