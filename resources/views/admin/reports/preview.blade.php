@extends('layouts.admin')

@section('title', 'Preview Laporan')

@section('content')

<div class="bg-white p-8 rounded-xl shadow border mx-auto">

    {{-- Gunakan styling PDF apa adanya --}}
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }

        .header-container {
            width: 100%;
            text-align: center;
            margin-bottom: 5px;
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
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .company-subtitle {
            font-size: 12px;
            margin-top: 2px;
        }

        .line {
            border-bottom: 2px solid #000;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #555;
            padding: 6px;
        }

        th {
            background: #f0f0f0;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        .title-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .title-section h2 {
            margin: 0;
            font-size: 16px;
        }

        .title-section h3 {
            margin: 3px 0;
            font-size: 14px;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
    </style>

    {{-- HEADER --}}
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td style="width: 90px;">
                    <img src="{{ asset('images/logo.png') }}" class="logo">
                </td>

                <td>
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

    {{-- TITLE --}}
    <div class="title-section">
        <h2>LAPORAN PENJUALAN</h2>
        <h3>Periode:
            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        </h3>

        <h3><strong>Total Pendapatan: Rp {{ number_format($totalRevenue) }}</strong></h3>
    </div>

    {{-- TOMBOL --}}
    <div class="flex gap-3 justify-center mb-4">
        <a href="{{ route('admin.reports.exportPdf', ['start_date'=>$startDate,'end_date'=>$endDate]) }}"
           class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            Download PDF
        </a>

        <a href="{{ route('admin.reports.exportExcel', ['start_date'=>$startDate,'end_date'=>$endDate]) }}"
           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            Download Excel
        </a>
    </div>

    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Pesanan</th>
                <th>Tanggal</th>
                <th>Pembeli</th>
                <th>Provinsi</th>
                <th>Kota/Kabupaten</th>
                <th>Alamat</th>
                <th>No. Telp</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            @php $no = 1; @endphp
            @foreach ($orders as $order)
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

    {{-- FOOTER --}}
    <div class="footer">
        <i>Dicetak pada: {{ now()->format('d M Y - H:i') }}</i>
    </div>

</div>

@endsection
