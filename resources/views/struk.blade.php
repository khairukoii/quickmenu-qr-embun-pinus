<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $order->id }} - Embun Pinus</title>
    <style>
        /* Desain Khusus Kertas Printer Thermal 58mm */
        @page { margin: 0; }
        body { font-family: 'Courier New', Courier, monospace; color: #000; font-size: 12px; margin: 0; padding: 10px; width: 58mm; line-height: 1.3; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mt-1 { margin-top: 5px; } .mb-1 { margin-bottom: 5px; }
        .mt-2 { margin-top: 10px; } .mb-2 { margin-bottom: 10px; }
        
        /* Garis putus-putus khas struk */
        .garis { border-top: 1px dashed #000; margin: 8px 0; }
        
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 2px 0; }
        
        /* Barcode bohongan untuk estetika */
        .barcode { font-size: 24px; letter-spacing: 2px; margin-top: 10px; font-weight: normal; font-family: sans-serif;}

        @media print { 
            .no-print { display: none !important; } 
            body { padding: 0; } 
        }
    </style>
</head>
<body>
    
    <div class="text-center font-bold" style="font-size: 16px;">EMBUN PINUS COFFEE</div>
    <div class="text-center" style="font-size: 10px; margin-top: 2px;">
        Jl. Cigombong Bohlam,<br>HR Edi Sukma, Ciburuy<br>
        IG: @embunpinus.coffee
    </div>

    <div class="garis mt-2"></div>

    <table>
        <tr><td class="text-left">Tgl</td><td class="text-right">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td></tr>
        <tr><td class="text-left">Nota</td><td class="text-right">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td></tr>
        <tr><td class="text-left">Meja</td><td class="text-right font-bold">{{ $order->table_number }}</td></tr>
        <tr><td class="text-left">Nama</td><td class="text-right font-bold uppercase">{{ $order->customer_name ?? 'TAMU' }}</td></tr>
    </table>

    <div class="garis"></div>

    <table>
        @foreach(json_decode($order->items) as $item)
        <tr>
            <td colspan="2" class="font-bold">{{ $item->name }}</td>
        </tr>
        <tr>
            <td class="text-left">{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="garis"></div>

    <table>
        <tr>
            <td class="text-left font-bold" style="font-size: 14px;">TOTAL</td>
            <td class="text-right font-bold" style="font-size: 14px;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-left">Metode Bayar</td>
            <td class="text-right font-bold uppercase">{{ $order->payment_method ?? 'CASH' }}</td>
        </tr>
        <tr>
            <td class="text-left">Status</td>
            <td class="text-right font-bold">LUNAS</td>
        </tr>
    </table>

    <div class="garis"></div>

    <div class="text-center mt-2" style="font-size: 10px;">
        Terima Kasih!<br>
        Password WiFi: pinussejuk123
    </div>
    
    <div class="text-center barcode">
        ||| | || ||| || || | |
    </div>

    <div class="text-center no-print" style="margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px; font-weight: bold; width: 100%; border: 2px solid #000; background: #fff; cursor:pointer; border-radius: 5px;">CETAK SEKARANG</button>
        <button onclick="window.close()" style="padding: 10px; font-weight: bold; width: 100%; border: none; background: #eee; cursor:pointer; border-radius: 5px; margin-top: 10px;">Tutup Tab</button>
    </div>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>