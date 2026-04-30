<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::orderBy('id', 'desc')->get();
        $completedOrders = Order::where('status', 'selesai')->get();
        $pesananPending = Order::where('status', 'pending')->count();
        
        $totalPendapatan = $completedOrders->sum('total_price');
        $totalPesanan = $completedOrders->count();
        $riwayatPesanan = Order::orderBy('id', 'desc')->take(10)->get();

        $now = Carbon::now();
        
        // 1. Pendapatan Tahun Ini
        $pendapatanTahunIni = $completedOrders->filter(function($order) use ($now) {
            return $order->updated_at->year == $now->year;
        })->sum('total_price');

        // 2. Pendapatan Bulan Ini
        $pendapatanBulanIni = $completedOrders->filter(function($order) use ($now) {
            return $order->updated_at->month == $now->month && $order->updated_at->year == $now->year;
        })->sum('total_price');

        // 3. Pendapatan Minggu Ini
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();
        $pendapatanMingguan = $completedOrders->filter(function($order) use ($startOfWeek, $endOfWeek) {
            return $order->updated_at->between($startOfWeek, $endOfWeek);
        })->sum('total_price');

        // Logika Grafik
        $mingguanLabels = []; $mingguanData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $mingguanLabels[] = $date->translatedFormat('D');
            $mingguanData[] = $completedOrders->whereBetween('updated_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])->sum('total_price');
        }

        $bulananLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $bulananData = [];
        for ($m = 1; $m <= 12; $m++) {
            $bulananData[] = $completedOrders->filter(function($order) use ($m, $now) {
                return $order->updated_at->month == $m && $order->updated_at->year == $now->year;
            })->sum('total_price');
        }

        $tahunanLabels = [$now->year - 2, $now->year - 1, $now->year];
        $tahunanData = [];
        foreach ($tahunanLabels as $tahun) {
            $tahunanData[] = $completedOrders->filter(function($order) use ($tahun) {
                return $order->updated_at->year == $tahun;
            })->sum('total_price');
        }

        return view('admin', compact(
            'menus', 'totalPendapatan', 'pendapatanTahunIni', 'pendapatanBulanIni', 'pendapatanMingguan',
            'totalPesanan', 'pesananPending', 'riwayatPesanan',
            'mingguanLabels', 'mingguanData', 'bulananLabels', 'bulananData', 'tahunanLabels', 'tahunanData'
        ));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'price' => 'required|numeric', 'category' => 'required', 'image' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
        $imagePath = $request->file('image')->store('menus', 'public');
        Menu::create(['name' => $request->name, 'price' => $request->price, 'category' => $request->category, 'image' => $imagePath, 'is_available' => true]);
        return back()->with('success', 'Menu berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        if ($menu->image && !str_starts_with($menu->image, 'http')) { Storage::disk('public')->delete($menu->image); }
        $menu->delete();
        return back()->with('success', 'Menu berhasil dihapus!');
    }

    public function dapur()
    {
        $pendingOrders = Order::where('status', 'pending')->orderBy('id', 'asc')->get();
        return view('dapur', compact('pendingOrders'));
    }

    public function selesaikanPesanan($id)
    {
        $order = Order::find($id);
        if ($order) { $order->update(['status' => 'selesai']); }
        return back()->with('success', 'Pesanan Meja ' . $order->table_number . ' Selesai Dimasak!');
    }

    // Fungsi untuk menampilkan desain struk kasir (Printer Thermal)
    public function cetakStruk($id)
    {
        $order = Order::findOrFail($id);
        return view('struk', compact('order'));
    }

    // Fungsi untuk toggle status menu (Tersedia / Tidak Tersedia)
    public function toggleStatus($id)
    {
        $menu = \App\Models\Menu::find($id);
        $menu->is_available = !$menu->is_available; // Balikkan statusnya
        $menu->save();

        return redirect()->back()->with('success', 'Status menu ' . $menu->name . ' berhasil diperbarui!');
    }

    public function update(Request $request, $id)
    {
        $menu = \App\Models\Menu::find($id);

        // Update data teks
        $menu->name = $request->name;
        $menu->price = $request->price;
        $menu->category = $request->category;

        // Jika checkbox status dicentang = true (Tersedia), jika tidak = false (Habis)
        $menu->is_available = $request->has('is_available') ? true : false;

        // Jika user mengupload foto baru, timpa foto lama
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
            $menu->image = $imagePath;
        }

        $menu->save();

        return redirect()->back()->with('success', 'Data menu ' . $menu->name . ' berhasil diperbarui!');
    }
}