<!-- resources/views/superadmin/reports/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Super Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #222;
            padding: 20px;
        }

        /* =====================
           KOP SURAT
        ===================== */
        .kop-wrapper {
            display: table;
            width: 100%;
            margin-bottom: 0;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }

        .kop-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .kop-logo img {
            width: 70px;
            height: 60px;
            object-fit: contain;
        }

        .kop-logo .logo-placeholder {
            width: 70px;
            height: 60px;
            background: #2D6A4F;
            border-radius: 6px;
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            color: white;
            font-size: 8px;
            font-weight: bold;
        }

        .kop-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 10px;
        }

        .kop-text h1 {
            font-size: 22px;
            font-weight: bold;
            color: #111;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

        .kop-text p {
            font-size: 10px;
            color: #444;
            margin-bottom: 2px;
        }

        /* =====================
           JUDUL LAPORAN
        ===================== */
        .report-title {
            text-align: center;
            margin-top: 14px;
            margin-bottom: 4px;
        }

        .report-title h2 {
            font-size: 16px;
            font-weight: bold;
            color: #111;
            letter-spacing: 0.5px;
        }

        .report-period {
            text-align: center;
            font-size: 10px;
            color: #444;
            margin-bottom: 6px;
        }

        /* =====================
           RINGKASAN STATISTIK
        ===================== */
        .stats-bar {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            color: #111;
            background: #F0F7F4;
            border: 1px solid #b7dbc8;
            border-radius: 4px;
            padding: 7px 10px;
            margin-bottom: 16px;
        }

        .stats-bar span {
            margin: 0 10px;
        }

        .stats-separator {
            color: #2D6A4F;
        }

        /* =====================
           TABEL DATA
        ===================== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            font-size: 8px;
        }

        table thead tr {
            background-color: #2D6A4F;
            color: #fff;
        }

        table th {
            padding: 7px 5px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            border: 1px solid #1d4d38;
        }

        table th.text-center {
            text-align: center;
        }

        table th.text-right {
            text-align: right;
        }

        table td {
            padding: 6px 5px;
            border: 1px solid #d1d5db;
            vertical-align: top;
        }

        table tbody tr.row-even {
            background-color: #F0F7F4;
        }

        table tbody tr.row-odd {
            background-color: #ffffff;
        }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .text-bold   { font-weight: bold; }
        .text-muted  { color: #666; font-size: 7.5px; }

        /* =====================
           TFOOT TOTAL
        ===================== */
        tfoot tr td {
            background: #F0F7F4;
            font-weight: bold;
            border-top: 2px solid #2D6A4F;
            font-size: 9px;
        }

        tfoot .total-label {
            text-align: right;
            padding-right: 8px;
        }

        tfoot .total-value {
            text-align: right;
            color: #2D6A4F;
            font-size: 10px;
        }

        /* =====================
           FOOTER
        ===================== */
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #ccc;
            text-align: right;
            font-size: 8.5px;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>

    <!-- =====================
         KOP SURAT
    ===================== -->
    <div class="kop-wrapper">
        <div class="kop-logo">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ public_path('images/logo.png') }}" alt="Logo">
            @else
                <table style="width:70px;height:60px;background:#2D6A4F;border-radius:6px;">
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

    <!-- =====================
         JUDUL LAPORAN
    ===================== -->
    <div class="report-title">
        <h2>LAPORAN SUPER ADMIN</h2>
    </div>
    <div class="report-period">
        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
        &nbsp;s/d&nbsp;
        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
    </div>

    <!-- =====================
         RINGKASAN STATISTIK
    ===================== -->
    <div class="stats-bar">
        <span>Total Pendapatan: Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
        <span class="stats-separator">&nbsp;|&nbsp;</span>
        <span>Total Pesanan: {{ number_format($stats['total_orders']) }}</span>
        <span class="stats-separator">&nbsp;|&nbsp;</span>
        <span>Total Item Terjual: {{ number_format($stats['total_items_sold']) }}</span>
    </div>

    <!-- =====================
         TABEL DATA
    ===================== -->
    <table>
        <thead>
            <tr>
                <th style="width:3%">No.</th>
                <th style="width:8%">No. Pesanan</th>
                <th style="width:8%">Tanggal</th>
                <th style="width:8%">Nama Pembeli</th>
                <th style="width:9%">Email Pembeli</th>
                <th style="width:7%">Provinsi</th>
                <th style="width:7%">Kota/Kabupaten</th>
                <th style="width:10%">Alamat Lengkap</th>
                <th style="width:6%">No. Telp</th>
                <th style="width:11%">Nama Produk</th>
                <th class="text-center" style="width:4%">Jml Item</th>
                <th class="text-right" style="width:5%">Subtotal</th>
                <th class="text-right" style="width:5%">Ongkir</th>
                <th class="text-right" style="width:5%">Total</th>
                <th style="width:7%">Metode Bayar</th>
                <th style="width:6%">Status Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
            @php
                $productNames = $order->items
                    ->map(fn($item) => ($item->product->name ?? '-') . ' (x' . $item->quantity . ')')
                    ->implode(', ');

                $totalItems = $order->items->sum('quantity');

                $paymentMethod = $order->payment_method ?? '-';
                if (stripos($paymentMethod, 'cod') !== false)
                    $paymentMethod = 'COD';
                elseif (stripos($paymentMethod, 'transfer') !== false)
                    $paymentMethod = 'Transfer Bank';
                elseif (stripos($paymentMethod, 'wallet') !== false)
                    $paymentMethod = 'E-Wallet';

                $paymentStatus = $order->payment_status ?? '-';
                if ($paymentStatus === 'paid') $paymentStatus = 'Lunas';

                $rowClass = (($index + 1) % 2 === 0) ? 'row-even' : 'row-odd';
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-bold">{{ $order->order_number ?? '-' }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $order->user->name ?? '-' }}</td>
                <td class="text-muted">{{ $order->user->email ?? '-' }}</td>
                <td>{{ $order->province ?? '-' }}</td>
                <td>{{ $order->city ?? '-' }}</td>
                <td>{{ $order->address?->full_address ?? $order->shipping_address ?? '-' }}</td>
                <td>{{ $order->recipient_phone ?? '-' }}</td>
                <td>{{ $productNames }}</td>
                <td class="text-center">{{ $totalItems }}</td>
                <td class="text-right">Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
                <td class="text-right text-bold">Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}</td>
                <td>{{ $paymentMethod }}</td>
                <td>{{ $paymentStatus }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="13" class="total-label">TOTAL PENDAPATAN:</td>
                <td class="total-value text-right">
                    Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <!-- =====================
         FOOTER
    ===================== -->
    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i') }} WIB
    </div>

</body>
</html>