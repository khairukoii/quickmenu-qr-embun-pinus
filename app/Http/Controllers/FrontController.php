<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;

class FrontController extends Controller
{
    public function index(Request $request)
    {
        $meja = $request->query('meja');
        $menus = Menu::where('is_available', true)->get();
        return view('welcome', compact('menus', 'meja'));
    }

    public function checkout(Request $request)
    {
        // CEK METODE PEMBAYARAN: Kalau Cash, statusnya "belum_dibayar", kalau E-Wallet langsung "pending"
        $statusAwal = ($request->payment_method === 'Cash') ? 'belum_dibayar' : 'pending';

        $order = \App\Models\Order::create([
            'table_number' => $request->table_number,
            'customer_name' => $request->customer_name,
            'payment_method' => $request->payment_method,
            'items' => json_encode($request->items),
            'total_price' => $request->total_price,
            'status' => $statusAwal
        ]);

        return response()->json([
            'message' => 'Pesanan berhasil dibuat.',
            'order_id' => $order->id,
            'status' => $statusAwal // Kirim status awal ke HP pelanggan
        ]);
    }

    public function checkStatus($id)
    {
        $order = Order::find($id);
        return response()->json(['status' => $order ? $order->status : 'unknown']);
    }
}