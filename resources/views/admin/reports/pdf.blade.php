{{-- resources/views/admin/reports/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 8px;
            line-height: 1.4;
            color: #222;
            padding: 18px;
        }

        /* KOP SURAT */
        .kop-wrapper { display: table; width: 100%; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 0; }
        .kop-logo { display: table-cell; width: 80px; vertical-align: middle; }
        .kop-logo img { width: 70px; height: 60px; object-fit: contain; }
        .kop-text { display: table-cell; vertical-align: middle; text-align: center; padding: 0 10px; }
        .kop-text h1 { font-size: 20px; font-weight: bold; color: #111; letter-spacing: 1px; margin-bottom: 3px; }
        .kop-text p  { font-size: 9.5px; color: #444; margin-bottom: 2px; }

        /* JUDUL */
        .report-title    { text-align: center; margin-top: 12px; margin-bottom: 3px; }
        .report-title h2 { font-size: 14px; font-weight: bold; color: #111; }
        .report-period   { text-align: center; font-size: 9px; color: #444; margin-bottom: 8px; }

        /* STATISTIK */
        .stats-wrapper { display: table; width: 100%; margin-bottom: 14px; border: 1px solid #b7dbc8; border-radius: 4px; background: #F0F7F4; }
        .stats-cell { display: table-cell; width: 25%; text-align: center; padding: 8px 6px; border-right: 1px solid #b7dbc8; vertical-align: middle; }
        .stats-cell:last-child { border-right: none; }
        .stats-label { font-size: 7.5px; color: #555; margin-bottom: 3px; text-transform: uppercase; }
        .stats-value { font-size: 11px; font-weight: bold; color: #2D6A4F; }

        /* TABEL */
        table { width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 7px; }
        thead tr { background-color: #2D6A4F; color: #fff; }
        th { padding: 5px 3px; font-size: 7px; font-weight: bold; border: 1px solid #1d4d38; text-align: left; }
        th.text-center { text-align: center; }
        th.text-right  { text-align: right; }
        td { padding: 4px 3px; border: 1px solid #d1d5db; vertical-align: top; }
        tr.row-even { background-color: #F0F7F4; }
        tr.row-odd  { background-color: #ffffff; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .text-bold   { font-weight: bold; }
        .text-muted  { color: #666; font-size: 6.5px; }

        /* STATUS BADGE */
        .badge            { padding: 1px 4px; border-radius: 3px; font-size: 6.5px; font-weight: bold; }
        .badge-pending    { background: #fef9c3; color: #854d0e; }
        .badge-paid       { background: #dbeafe; color: #1e40af; }
        .badge-processing { background: #f3e8ff; color: #6b21a8; }
        .badge-shipped    { background: #e0e7ff; color: #3730a3; }
        .badge-completed  { background: #dcfce7; color: #15803d; }
        .badge-cancelled  { background: #fee2e2; color: #991b1b; }

        /* TFOOT */
        tfoot td { background: #e8f5e9; font-weight: bold; border-top: 2px solid #2D6A4F; font-size: 8px; }

        /* FOOTER */
        .footer { margin-top: 16px; padding-top: 6px; border-top: 1px solid #ccc; text-align: right; font-size: 8px; font-style: italic; color: #666; }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="kop-wrapper">
        <div class="kop-logo">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ public_path('images/logo.png') }}" alt="Logo">
            @else
                <table style="width:70px;height:60px;background:#2D6A4F;border-radius:4px;">
                    <tr><td style="text-align:center;color:#fff;font-weight:bold;font-size:8px;padding:4px;">LOGO</td></tr>
                </table>
            @endif
        </div>
        <div class="kop-text">
            <h1>E-COMMERCE TSA</h1>
            <p>Jl. Raya Nasional 12 No. 45 - Bandar Lampung</p>
            <p>Email: admin@ecommerce-tsa.com &nbsp;|&nbsp; Telp: 0822-1234-5678</p>
        </div>
    </div>

    {{-- JUDUL --}}
    <div class="report-title"><h2>LAPORAN PENJUALAN</h2></div>
    <div class="report-period">
        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
    </div>

    {{-- RINGKASAN STATISTIK --}}
    <div class="stats-wrapper">
        <div class="stats-cell">
            <div class="stats-label">Total Pendapatan</div>
            <div class="stats-value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
        </div>
        <div class="stats-cell">
            <div class="stats-label">Total Pesanan</div>
            <div class="stats-value">{{ number_format($stats['total_orders']) }}</div>
        </div>
        <div class="stats-cell">
            <div class="stats-label">Total Item Terjual</div>
            <div class="stats-value">{{ number_format($stats['total_items_sold']) }}</div>
        </div>
        <div class="stats-cell">
            <div class="stats-label">Rata-rata Pesanan</div>
            <div class="stats-value">Rp {{ number_format($stats['avg_order_value'], 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- TABEL --}}
    {{-- Kolom: No | No.Pesanan | Tanggal | Pembeli | Email | No.Telp | Provinsi | Kota/Kab | Alamat | Produk | Jumlah | Total | Status --}}
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:2%">No.</th>
                <th style="width:8%">No. Pesanan</th>
                <th style="width:6%">Tanggal</th>
                <th style="width:7%">Pembeli</th>
                <th style="width:10%">Email</th>
                <th style="width:7%">No. Telp</th>
                <th style="width:7%">Provinsi</th>
                <th style="width:7%">Kota/Kab.</th>
                <th style="width:13%">Alamat Lengkap</th>
                <th style="width:16%">Produk</th>
                <th class="text-center" style="width:4%">Jumlah</th>
                <th class="text-right" style="width:8%">Total</th>
                <th class="text-center" style="width:5%">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($orders as $index => $order)
            @php
                $products   = $order->items->map(fn($item) => ($item->product?->name ?? '-') . ' (x' . $item->quantity . ')')->implode(', ');
                $qty        = $order->items->sum('quantity');
                $grandTotal += $order->grand_total ?? 0;

                $statusLabels = [
                    'pending'    => 'Menunggu',
                    'paid'       => 'Dibayar',
                    'processing' => 'Diproses',
                    'shipped'    => 'Dikirim',
                    'completed'  => 'Selesai',
                    'cancelled'  => 'Dibatalkan',
                ];
                $statusLabel = $statusLabels[$order->status] ?? ucfirst($order->status);
                $badgeClass  = 'badge badge-' . ($order->status ?? 'pending');
                $rowClass    = (($index + 1) % 2 === 0) ? 'row-even' : 'row-odd';
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-bold">{{ $order->order_number ?? '-' }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}<br><span class="text-muted">{{ $order->created_at->format('H:i') }}</span></td>
                <td>{{ $order->recipient_name ?? $order->user?->name ?? '-' }}</td>
                <td class="text-muted">{{ $order->user?->email ?? '-' }}</td>
                <td>{{ $order->recipient_phone ?? '-' }}</td>
                <td>{{ $order->province ?? '-' }}</td>
                <td>{{ $order->city ?? '-' }}</td>
                <td>{{ $order->address?->full_address ?? $order->shipping_address ?? '-' }}</td>
                <td>{{ $products }}</td>
                <td class="text-center">{{ $qty }}</td>
                <td class="text-right text-bold">Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}</td>
                <td class="text-center"><span class="{{ $badgeClass }}">{{ $statusLabel }}</span></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="11" style="text-align:right; padding-right:8px;">TOTAL PENDAPATAN:</td>
                <td class="text-right" style="color:#2D6A4F; font-size:9px;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    {{-- FOOTER --}}
    <div class="footer">Dicetak pada: {{ now()->format('d M Y H:i') }} WIB</div>

</body>
</html>