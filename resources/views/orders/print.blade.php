<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Order - {{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 10px 0; }
        .header { margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 18px; }
        .header p { margin: 2px 0; }
        .info-table, .item-table { width: 100%; border-collapse: collapse; }
        .item-table th { text-align: left; border-bottom: 1px solid #000; padding: 5px 0; }
        .item-table td { padding: 5px 0; vertical-align: top; }
        .footer { margin-top: 20px; font-size: 10px; }
        @media print {
            body { width: 100%; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Struk</button>
        <a href="{{ route('orders.show', $order) }}" style="padding: 10px 20px; text-decoration: none; background: #eee; color: #000; border: 1px solid #ccc; margin-left: 10px;">Kembali</a>
    </div>

    <div class="header text-center">
        <h2>Gunawan's Laundry</h2>
        <!-- <p>Jl. Contoh No. 123, Kota Anda</p> -->
        <p>Telp: 0812-3456-7890</p>
    </div>

    <div class="line"></div>

    <table class="info-table">
        <tr>
            <td>Kode</td>
            <td>: {{ $order->order_code }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ $order->order_date->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td>: {{ $order->customer->customer_name ?? $order->guest_name ?? '-' }}</td>
        </tr>
        @if($order->customer->phone ?? $order->guest_phone)
        <tr>
            <td>Telp</td>
            <td>: {{ $order->customer->phone ?? $order->guest_phone }}</td>
        </tr>
        @endif
    </table>

    <div class="line"></div>

    <table class="item-table">
        <thead>
            <tr>
                <th>Layanan</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $d)
            <tr>
                <td>{{ $d->service->service_name }}</td>
                <td class="text-right">{{ $d->qty }}g</td>
                <td class="text-right">{{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table class="info-table">
        <tr>
            <td class="bold">TOTAL</td>
            <td class="text-right bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="text-right">Rp {{ number_format($order->order_pay, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="text-right">Rp {{ number_format($order->order_change, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="footer text-center">
        <p>Terima kasih telah mempercayai layanan kami!</p>
        <p>Barang yang sudah diambil tidak dapat dikomplain.</p>
        <p>{{ date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
