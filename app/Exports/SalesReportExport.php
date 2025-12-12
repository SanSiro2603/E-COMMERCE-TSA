<?php
// app/Exports/SalesReportExport.php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class SalesReportExport implements FromCollection, WithEvents, WithDrawings
{
    protected $startDate;
    protected $endDate;
    protected $totalRevenue;
    protected $orders;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $this->orders = Order::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', 'completed')
            ->with(['user', 'address', 'items.product']) // DITAMBAHKAN product
            ->orderBy('created_at', 'asc')
            ->get();

        // Tambahkan jumlah transaksi per pengguna di periode ini
        $this->orders->each(function($order) {
            $order->transactions_count = Order::where('user_id', $order->user_id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$this->startDate, $this->endDate])
                ->count();
        });

        // Hitung total revenue
        $this->totalRevenue = $this->orders->sum('grand_total');

        return collect([]); // sheet kosong, diisi manual pada AfterSheet
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath(public_path('images/logo.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // =====================
                // KOP SURAT
                // =====================
                $sheet->mergeCells('B1:I2');
                $sheet->setCellValue('B1', 'E-COMMERCE TSA');
                $sheet->getStyle('B1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->mergeCells('B3:I3');
                $sheet->setCellValue('B3', 'Jl. Raya Nasional 12 No. 45 - Bandar Lampung');

                $sheet->mergeCells('B4:I4');
                $sheet->setCellValue('B4', 'Email: admin@ecommerce-tsa.com | Telp: 0822-1234-5678');

                $sheet->getStyle('B3:B4')->applyFromArray([
                    'font' => ['size' => 11],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->mergeCells('A5:L5');
                $sheet->getStyle('A5:L5')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // =====================
                // JUDUL LAPORAN
                // =====================
                $sheet->mergeCells('A7:L7');
                $sheet->setCellValue('A7', 'LAPORAN PENJUALAN');
                $sheet->getStyle('A7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->mergeCells('A8:L8');
                $periodText =
                    'Periode: ' .
                    \Carbon\Carbon::parse($this->startDate)->format('d M Y') .
                    ' - ' .
                    \Carbon\Carbon::parse($this->endDate)->format('d M Y');

                $sheet->setCellValue('A8', $periodText);

                $sheet->mergeCells('A9:L9');
                $sheet->setCellValue('A9', 'Total Pendapatan: Rp ' . number_format($this->totalRevenue, 0, ',', '.'));

                // =====================
                // HEADER TABEL
                // =====================
                $headerRow = 11;
                $headers = [
                    'No.', 'No. Pesanan', 'Tanggal', 'Pembeli', 'Provinsi',
                    'Kota/Kabupaten', 'Alamat Lengkap', 'No. Telp',
                    'Nama Produk', 'Jumlah Item',
                    'Metode Pembayaran', 'Status Pembayaran', 'Total'
                ];
                $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];

                foreach ($headers as $i => $header) {
                    $sheet->setCellValue($columns[$i] . $headerRow, $header);
                }

                // =====================
                // ISI DATA
                // =====================
                $dataStartRow = 12;
                $currentRow = $dataStartRow;
                $no = 1;

                foreach ($this->orders as $order) {

                    $totalItems = $order->items->sum('quantity');

                    // ================================
                    // 👍 PERBAIKAN + TAMBAHAN DI SINI
                    // ================================
                    // ambil semua nama produk dalam satu order
                    $productNames = $order->items
                        ->map(fn($item) => $item->product->name ?? '-')
                        ->implode(", ");
                    // =================================

                    // metode pembayaran
                    $paymentMethod = $order->payment_method ?? 'Transfer Bank';
                    if (stripos($paymentMethod, 'cod') !== false) $paymentMethod = 'COD (Cash on Delivery)';
                    elseif (stripos($paymentMethod, 'transfer') !== false) $paymentMethod = 'Transfer Bank';
                    elseif (stripos($paymentMethod, 'wallet') !== false) $paymentMethod = 'E-Wallet';

                    // status
                    $paymentStatus = $order->payment_status ?? 'Lunas';
                    if ($paymentStatus === 'paid') $paymentStatus = 'Lunas';

                    // tulis data
                    $sheet->setCellValue('A' . $currentRow, $no++);
                    $sheet->setCellValue('B' . $currentRow, $order->order_number);
                    $sheet->setCellValue('C' . $currentRow, $order->created_at->format('d/m/Y'));
                    $sheet->setCellValue('D' . $currentRow, $order->recipient_name ?? $order->user->name);
                    $sheet->setCellValue('E' . $currentRow, $order->province ?? '-');
                    $sheet->setCellValue('F' . $currentRow, $order->city ?? '-');
                    $sheet->setCellValue('G' . $currentRow, $order->address?->full_address ?? $order->shipping_address ?? '-');
                    $sheet->setCellValue('H' . $currentRow, $order->recipient_phone ?? '-');
                    $sheet->setCellValue('I' . $currentRow, $productNames);
                    $sheet->setCellValue('J' . $currentRow, $totalItems);

                    // ----------------------------
                    // ➕ Nama Produk (DITAMBAHKAN)
                    // ----------------------------
                    $sheet->setCellValue('K' . $currentRow, $paymentMethod);

                    $sheet->setCellValue('L' . $currentRow, $paymentStatus);
                    $sheet->setCellValue('M' . $currentRow, 'Rp ' . number_format($order->grand_total, 0, ',', '.'));

                    $currentRow++;
                }

                // =====================
                // STYLE & FORMAT
                // =====================
                $lastDataRow = $currentRow - 1;

                if ($lastDataRow >= $dataStartRow) {
                    $sheet->getStyle('A'.$dataStartRow.':M'.$lastDataRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }

                // set column width
                $sheet->getColumnDimension('K')->setWidth(30); // Nama Produk lebih lebar
            },
        ];
    }
}
