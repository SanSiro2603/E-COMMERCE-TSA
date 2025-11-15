{{-- resources/views/admin/reports/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="header">
        <h1>E-Commerce TSA</h1>
        <h2>Laporan Penjualan</h2>
        <p>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        <p><strong>Total Pendapatan: Rp {{ number_format($totalRevenue) }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Pesanan</th>
                <th>Tanggal</th>
                <th>Nama Pembeli</th>
                <th>Provinsi</th>
                <th>Kota</th>
                <th>Alamat</th>
                <th>No. Telp</th>
                {{-- <th>Jumlah Transaksi</th> --}}
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
                    <td>{{ $order->province ?? '-' }}</td>
                    <td>{{ $order->city ?? '-' }}</td>
                    <td>{{ $order->address?->full_address ?? '-' }}</td>
                    <td>{{ $order->recipient_phone ?? '-' }}</td>
                   {{-- <td>{{ $order->transactions_count }}</td>bisa diganti dengan jumlah item --}}
                    <td>Rp {{ number_format($order->grand_total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
