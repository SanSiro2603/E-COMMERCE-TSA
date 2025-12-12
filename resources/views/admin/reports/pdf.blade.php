<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 30px 40px;
        }

        .header-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .logo {
            height: 80px;        /* sesuaikan ukuran logo kamu */
            width: auto;
            margin-bottom: 10px;
        }

        .company-title {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.2;
        }

        .company-subtitle {
            font-size: 11px;
            margin: 8px 0 0;
            line-height: 1.5;
        }

        .line {
            border-bottom: 3px double #000;
            margin: 18px 0;
        }

        .title-section { text-align: center; margin-bottom: 25px; }
        .title-section h2 { margin: 0; font-size: 18px; font-weight: bold; text-transform: uppercase; }
        .title-section h3 { margin: 8px 0 0; font-size: 13px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10.5px;
        }
        th {
            background: #e6e6e6;
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #999;
        }
        td {
            padding: 9px 8px;
            border: 1px solid #bbb;
            vertical-align: middle !important;
        }
        tr:nth-child(even) { background: #f9f9f9; }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .total-price { text-align: right; font-weight: bold; white-space: nowrap; font-size: 11px; }
        .product-list { line-height: 1.5; }
        .address { font-size: 10px; line-height: 1.4; }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>
<body>

    <!-- HEADER DENGAN LOGO -->
    <!-- HEADER DENGAN LOGO DI KIRI + GARIS FULL -->
<div class="header-container">
    <table style="width: 100%; margin-bottom: 15px;">
        <tr>
            <!-- LOGO DI KIRI -->
            <td style="width: 100px; vertical-align: middle;">
                <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="height: 85px; width: auto;">
            </td>

            <!-- KOP PERUSAHAAN DI TENGAH -->
            <td style="text-align: center; vertical-align: middle;">
                <div class="company-title">E-COMMERCE TSA</div>
                <div class="company-subtitle">
                    Jl. Raya Nasional 12 No. 45 - Bandar Lampung<br>
                    Email: admin@ecommerce-tsa.com | Telp: 0822-1234-5678
                </div>
            </td>

            <!-- KOSONGIN KANAN SUPAYA RATA -->
            <td style="width: 100px;"></td>
        </tr>
    </table>

    <!-- GARIS BAWAH FULL DARI UJUNG KE UJUNG -->
    <div style="border-bottom: 3px double #000; margin-top: 10px;"></div>
</div>

    <!-- JUDUL LAPORAN -->
    <div class="title-section">
        <h2>Laporan Penjualan</h2>
        <h3>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</h3>
        <h3><strong>Total Pendapatan: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></h3>
    </div>

    <!-- TABEL (sama seperti sebelumnya, tetap rapi) -->
    <table>
        <thead>
            <tr>
                <th width="4%">No.</th>
                <th width="11%">No. Pesanan</th>
                <th width="8%">Tanggal</th>
                <th width="10%">Pembeli</th>
                <th width="9%">Provinsi</th>
                <th width="10%">Kota/Kab.</th>
                <th width="18%">Alamat Lengkap</th>
                <th width="9%">No. Telp</th>
                <th width="12%">Produk</th>
                <th width="6%">Jumlah</th>
                <th width="10%">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($orders->sortBy('created_at') as $order)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $order->order_number }}</td>
                <td class="text-center">{{ $order->created_at->format('d/m/Y') }}</td>
                <td>{{ $order->recipient_name }}</td>
                <td>{{ $order->province }}</td>
                <td>{{ $order->city }}</td>
                <td class="address">{{ $order->address?->full_address ?? $order->shipping_address ?? '-' }}</td>
                <td>{{ $order->recipient_phone }}</td>
                <td class="product-list">
                    @foreach($order->items as $item)
                        • {{ $item->product->name }} ({{ $item->quantity }}x)<br>
                    @endforeach
                </td>
                <td class="text-center">{{ $order->items->sum('quantity') }} item</td>
                <td class="total-price">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <i>Dicetak pada: {{ now()->format('d M Y - H:i') }} WIB</i>
    </div>

</body>
</html>