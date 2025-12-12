@extends('layouts.admin')

@section('title', 'Preview Laporan Penjualan')

@section('content')

<div class="p-4 md:p-8">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">

        <style>
            .report-container {
                max-width: 100%;
                padding: 40px 30px;
                font-family: 'DejaVu Sans', Arial, sans-serif;
            }
            @media (max-width: 1024px) {
                .report-container { padding: 30px 20px; }
            }

            /* Header Kop */
            .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            .header-table td { vertical-align: middle; }
            .logo { height: 80px; width: auto; }
            .company-title { font-size: 26px; font-weight: bold; text-transform: uppercase; text-align: center; color: #1e293b; margin: 0; }
            .company-subtitle { font-size: 12px; text-align: center; color: #475569; margin-top: 6px; line-height: 1.5; }
            .line { border-bottom: 3px double #1e293b; margin: 20px 0 10px; }

            /* Judul */
            .title-section { text-align: center; margin: 30px 0; }
            .title-section h2 { font-size: 22px; font-weight: bold; text-transform: uppercase; color: #1e293b; margin: 0; }
            .title-section h3 { font-size: 15px; color: #334155; margin: 12px 0 0; }

            /* Tombol */
            .btn-group { text-align: center; margin-bottom: 30px; }
            .btn {
                display: inline-block;
                padding: 12px 30px;
                margin: 0 10px;
                border-radius: 50px;
                color: white;
                font-weight: bold;
                text-decoration: none;
                box-shadow: 0 6px 15px rgba(0,0,0,0.15);
                transition: all 0.3s;
            }
            .btn-pdf { background: #dc3545; }
            .btn-pdf:hover { background: #c82333; transform: translateY(-2px); }
            .btn-excel { background: #28a745; }
            .btn-excel:hover { background: #218838; transform: translateY(-2px); }

            /* TABEL */
            .report-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 11px;
                background: white;
            }
            .report-table th {
                background: #1e293b;
                color: white;
                padding: 12px 8px;
                text-align: center;
                font-weight: bold;
                font-size: 10.5px;
                white-space: nowrap;
            }
            .report-table td {
                padding: 10px 8px;
                border-bottom: 1px solid #e2e8f0;
                vertical-align: middle;
                word-wrap: break-word;
                color: #475569 !important;   /* <<< SEMUA DATA PAKAI WARNA INI (sama kayak alamat) */
                font-weight: 500;
            }
            .report-table tr:nth-child(even) td { background: #f8fafc; }
            .report-table tr:hover td { background: #fef3c7; }

            /* Kolom khusus tetap pakai warna ini juga */
            .col-address,
            .col-product,
            td {
                color: #ffffff !important;
                font-size: 10.8px;
                line-height: 1.5;
            }

            /* Hanya Total Harga yang tetap merah */
            .col-total {
                text-align: right !important;
                font-weight: bold !important;
                color: #ffffff !important;
                white-space: nowrap;
                font-size: 11.8px !important;
            }

            /* Lebar kolom anti meluber */
            .col-no { width: 4%; text-align: center; }
            .col-order { width: 10%; }
            .col-date { width: 7%; text-align: center; }
            .col-buyer { width: 9%; }
            .col-prov { width: 8%; }
            .col-city { width: 9%; }
            .col-address { width: 18%; }
            .col-phone { width: 9%; text-align: center; }
            .col-product { width: 14%; }
            .col-qty { width: 6%; text-align: center; }
            .col-total { width: 10%; }

            .footer {
                margin-top: 50px;
                text-align: right;
                font-size: 11px;
                color: #64748b;
                font-style: italic;
            }
        </style>

        <div class="report-container">

            <!-- HEADER -->
            <table class="header-table">
                <tr>
                    <td style="width: 100px;"><img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo"></td>
                    <td>
                        <div class="company-title">E-COMMERCE TSA</div>
                        <div class="company-subtitle">
                            Jl. Raya Nasional 12 No. 45 - Bandar Lampung<br>
                            Email: admin@ecommerce-tsa.com | Telp: 0822-1234-5678
                        </div>
                    </td>
                    <td style="width: 100px;"></td>
                </tr>
            </table>
            <div class="line"></div>

            <!-- JUDUL -->
            <div class="title-section">
                <h2>Laporan Penjualan</h2>
                <h3>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</h3>
                <h3><strong>Total Pendapatan: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></h3>
            </div>

            <!-- TOMBOL -->
            <div class="btn-group">
                <a href="{{ route('admin.reports.exportPdf', ['start_date'=>$startDate, 'end_date'=>$endDate]) }}" class="btn btn-pdf" target="_blank">
                    Download PDF
                </a>
                <a href="{{ route('admin.reports.exportExcel', ['start_date'=>$startDate, 'end_date'=>$endDate]) }}" class="btn btn-excel">
                    Download Excel
                </a>
            </div>

            <!-- TABEL -->
            <div style="overflow-x: auto;">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th class="col-no">No.</th>
                            <th class="col-order">No. Pesanan</th>
                            <th class="col-date">Tanggal</th>
                            <th class="col-buyer">Pembeli</th>
                            <th class="col-prov">Provinsi</th>
                            <th class="col-city">Kota/Kab.</th>
                            <th class="col-address">Alamat Lengkap</th>
                            <th class="col-phone">No. Telp</th>
                            <th class="col-product">Produk</th>
                            <th class="col-qty">Jumlah</th>
                            <th class="col-total">Total</th>
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
                            <td class="col-address">{{ $order->address?->full_address ?? $order->shipping_address ?? '-' }}</td>
                            <td class="text-center">{{ $order->recipient_phone }}</td>
                            <td class="col-product">
                                @foreach($order->items as $item)
                                    • {{ $item->product->name }} ({{ $item->quantity }}x)<br>
                                @endforeach
                            </td>
                            <td class="text-center">{{ $order->items->sum('quantity') }} item</td>
                            <td class="col-total">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- FOOTER -->
            <div class="footer">
                Dicetak pada: {{ now()->format('d M Y - H:i') }} WIB
            </div>
        </div>
    </div>
</div>
@endsection