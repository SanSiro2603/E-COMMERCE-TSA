<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        /* ===================== */
        /*      KOP SURAT        */
        /* ===================== */
        .header-container {
            width: 100%;
            margin-bottom: 10px;
        }

        .header-table {
            width: 100%;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .company-title {
            text-align: center !important;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            display: block;
            width: 100%;
        }

        .company-subtitle {
            font-size: 12px;
            margin-top: 2px;
            text-align: center !important;
            display: block;
            width: 100%;
            line-height: 1.4;
        }

        .kop-center {
            text-align: center !important;
        }

        .line {
            border-bottom: 2px solid #000;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        /* ===================== */
        /*     JUDUL LAPORAN     */
        /* ===================== */
        .title-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .title-section h2 {
            margin: 0;
            font-size: 16px;
        }

        .title-section h3 {
            margin: 4px 0;
            font-size: 14px;
        }

        /* ===================== */
        /*      TABEL DATA       */
        /* ===================== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th, td {
            border: 1px solid #bbb;
            padding: 7px;
        }

        th {
            background: #f3f3f3;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        /* ===================== */
        /*         FOOTER        */
        /* ===================== */
        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 12px;
        }
    </style>

</head>

<body>

    <!-- ===================== -->
    <!--       HEADER / KOP     -->
    <!-- ===================== -->
    <div class="header-container">
        <table class="header-table">
            <tr>
                <!-- LOGO -->
                <td style="width: 90px;">
                    <img src="{{ public_path('images/logo.png') }}" class="logo">
                </td>

                <!-- NAMA & ALAMAT -->
                <td class="kop-center">
                    <div class="company-title">E-COMMERCE TSA</div>

                    <div class="company-subtitle">
                        Jl. Raya Nasional 12 No. 45 - Bandar Lampung<br>
                        Email: admin@ecommerce-tsa.com | Telp: 0822-1234-5678
                    </div>
                </td>

                <td style="width: 90px;"></td>
            </tr>
        </table>

        <div class="line"></div>
    </div>

    <!-- ===================== -->
    <!--     JUDUL LAPORAN     -->
    <!-- ===================== -->
    <div class="title-section">
        <h2>LAPORAN PENJUALAN</h2>
        <h3>
            Periode:
            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
            -
            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        </h3>

        <h3><strong>Total Pendapatan: Rp {{ number_format($totalRevenue) }}</strong></h3>
    </div>

    <!-- ===================== -->
    <!--        TABEL          -->
    <!-- ===================== -->
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Pesanan</th>
                <th>Tanggal</th>
                <th>Pembeli</th>
                <th>Provinsi</th>
                <th>Kota/Kabupaten</th>
                <th>Alamat Lengkap</th>
                <th>No. Telp</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            @php $no = 1; @endphp
            @foreach($orders->sortBy('created_at') as $order)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>{{ $order->recipient_name }}</td>
                <td>{{ $order->province }}</td>
                <td>{{ $order->city }}</td>
                <td>{{ $order->address?->full_address ?? $order->shipping_address ?? '-' }}</td>
                <td>{{ $order->recipient_phone }}</td>
                <td>Rp {{ number_format($order->grand_total) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ===================== -->
    <!--        FOOTER         -->
    <!-- ===================== -->
    <div class="footer">
        <i>Dicetak pada: {{ now()->format('d M Y - H:i') }}</i>
    </div>

</body>
</html>
s