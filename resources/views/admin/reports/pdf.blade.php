{{-- resources/views/admin/reports/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
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
                <th>No. Pesanan</th>
                <th>Pembeli</th>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($order->grand_total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>