<?php
// app/Exports/SuperAdminReportExport.php

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

class SuperAdminReportExport implements FromCollection, WithEvents, WithDrawings
{
    protected $startDate;
    protected $endDate;
    protected $totalRevenue;
    protected $totalOrders;
    protected $totalItemsSold;
    protected $orders;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        $this->orders = Order::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', 'completed')
            ->with(['user', 'items.product', 'address'])
            ->orderBy('created_at', 'asc')
            ->get();

        $this->totalRevenue    = $this->orders->sum('grand_total');
        $this->totalOrders     = $this->orders->count();
        $this->totalItemsSold  = $this->orders->sum(fn($order) => $order->items->sum('quantity'));

        return collect([]);
    }

    public function drawings()
    {
        $logoPath = public_path('images/logo.png');

        if (!file_exists($logoPath)) {
            return [];
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath($logoPath);
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // =====================
                // KOP SURAT
                // =====================
                $sheet->mergeCells('B1:P2');
                $sheet->setCellValue('B1', 'E-COMMERCE TSA');
                $sheet->getStyle('B1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 18],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->mergeCells('B3:P3');
                $sheet->setCellValue('B3', 'Jl. Raya Nasional 12 No. 45 - Bandar Lampung');
                $sheet->getStyle('B3')->applyFromArray([
                    'font' => ['size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('B4:P4');
                $sheet->setCellValue('B4', 'Email: admin@ecommerce-tsa.com | Telp: 0822-1234-5678');
                $sheet->getStyle('B4')->applyFromArray([
                    'font' => ['size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Garis pemisah kop
                $sheet->mergeCells('A5:P5');
                $sheet->getStyle('A5:P5')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color'       => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // =====================
                // JUDUL LAPORAN
                // =====================
                $sheet->mergeCells('A7:P7');
                $sheet->setCellValue('A7', 'LAPORAN SUPER ADMIN');
                $sheet->getStyle('A7')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A8:P8');
                $sheet->setCellValue('A8',
                    'Periode: ' .
                    \Carbon\Carbon::parse($this->startDate)->format('d M Y') .
                    ' s/d ' .
                    \Carbon\Carbon::parse($this->endDate)->format('d M Y')
                );
                $sheet->getStyle('A8')->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // =====================
                // RINGKASAN STATISTIK
                // =====================
                $sheet->mergeCells('A9:P9');
                $sheet->setCellValue('A9',
                    'Total Pendapatan: Rp ' . number_format($this->totalRevenue, 0, ',', '.') .
                    '   |   Total Pesanan: ' . $this->totalOrders .
                    '   |   Total Item Terjual: ' . $this->totalItemsSold
                );
                $sheet->getStyle('A9')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // =====================
                // HEADER TABEL (baris 11)
                // =====================
                $headerRow = 11;
                $headers = [
                    'A' => 'No.',
                    'B' => 'No. Pesanan',
                    'C' => 'Tanggal',
                    'D' => 'Nama Pembeli',
                    'E' => 'Email Pembeli',
                    'F' => 'Provinsi',
                    'G' => 'Kota/Kabupaten',
                    'H' => 'Alamat Lengkap',
                    'I' => 'No. Telp',
                    'J' => 'Nama Produk',
                    'K' => 'Jumlah Item',
                    'L' => 'Subtotal',
                    'M' => 'Ongkos Kirim',
                    'N' => 'Total',
                    'O' => 'Metode Pembayaran',
                    'P' => 'Status Pembayaran',
                ];

                foreach ($headers as $col => $label) {
                    $sheet->setCellValue($col . $headerRow, $label);
                }

                $sheet->getStyle('A' . $headerRow . ':P' . $headerRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2D6A4F'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // =====================
                // ISI DATA
                // =====================
                $currentRow = 12;
                $no         = 1;

                foreach ($this->orders as $order) {
                    $productNames = $order->items
                        ->map(fn($item) => ($item->product->name ?? '-') . ' (x' . $item->quantity . ')')
                        ->implode(', ');

                    $totalItems = $order->items->sum('quantity');

                    $paymentMethod = $order->payment_method ?? '-';
                    if (stripos($paymentMethod, 'cod') !== false)      $paymentMethod = 'COD (Cash on Delivery)';
                    elseif (stripos($paymentMethod, 'transfer') !== false) $paymentMethod = 'Transfer Bank';
                    elseif (stripos($paymentMethod, 'wallet') !== false)   $paymentMethod = 'E-Wallet';

                    $paymentStatus = $order->payment_status ?? '-';
                    if ($paymentStatus === 'paid') $paymentStatus = 'Lunas';

                    $sheet->setCellValue('A' . $currentRow, $no++);
                    $sheet->setCellValue('B' . $currentRow, $order->order_number ?? '-');
                    $sheet->setCellValue('C' . $currentRow, $order->created_at->format('d/m/Y H:i'));
                    $sheet->setCellValue('D' . $currentRow, $order->user->name ?? '-');
                    $sheet->setCellValue('E' . $currentRow, $order->user->email ?? '-');
                    $sheet->setCellValue('F' . $currentRow, $order->province ?? '-');
                    $sheet->setCellValue('G' . $currentRow, $order->city ?? '-');
                    $sheet->setCellValue('H' . $currentRow, $order->address?->full_address ?? $order->shipping_address ?? '-');
                    $sheet->setCellValue('I' . $currentRow, $order->recipient_phone ?? '-');
                    $sheet->setCellValue('J' . $currentRow, $productNames);
                    $sheet->setCellValue('K' . $currentRow, $totalItems);
                    $sheet->setCellValue('L' . $currentRow, 'Rp ' . number_format($order->subtotal ?? 0, 0, ',', '.'));
                    $sheet->setCellValue('M' . $currentRow, 'Rp ' . number_format($order->shipping_cost ?? 0, 0, ',', '.'));
                    $sheet->setCellValue('N' . $currentRow, 'Rp ' . number_format($order->grand_total ?? 0, 0, ',', '.'));
                    $sheet->setCellValue('O' . $currentRow, $paymentMethod);
                    $sheet->setCellValue('P' . $currentRow, $paymentStatus);

                    // Zebra stripe (baris genap sedikit berbeda warna)
                    if ($no % 2 === 0) {
                        $sheet->getStyle('A' . $currentRow . ':P' . $currentRow)->applyFromArray([
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F0F7F4'],
                            ],
                        ]);
                    }

                    $currentRow++;
                }

                // =====================
                // BORDER SELURUH DATA
                // =====================
                $lastDataRow = $currentRow - 1;

                if ($lastDataRow >= 12) {
                    $sheet->getStyle('A12:P' . $lastDataRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);
                }

                // =====================
                // LEBAR KOLOM
                // =====================
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(22);
                $sheet->getColumnDimension('E')->setWidth(28);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(22);
                $sheet->getColumnDimension('H')->setWidth(35);
                $sheet->getColumnDimension('I')->setWidth(18);
                $sheet->getColumnDimension('J')->setWidth(40);
                $sheet->getColumnDimension('K')->setWidth(14);
                $sheet->getColumnDimension('L')->setWidth(18);
                $sheet->getColumnDimension('M')->setWidth(16);
                $sheet->getColumnDimension('N')->setWidth(18);
                $sheet->getColumnDimension('O')->setWidth(24);
                $sheet->getColumnDimension('P')->setWidth(20);

                // Tinggi baris header
                $sheet->getRowDimension($headerRow)->setRowHeight(25);

                // Wrap text kolom produk & alamat lengkap
                $sheet->getStyle('H12:H' . $lastDataRow)->getAlignment()->setWrapText(true);
                $sheet->getStyle('J12:J' . $lastDataRow)->getAlignment()->setWrapText(true);

                // =====================
                // FOOTER / CATATAN
                // =====================
                $footerRow = $lastDataRow + 2;
                $sheet->mergeCells('A' . $footerRow . ':P' . $footerRow);
                $sheet->setCellValue('A' . $footerRow,
                    'Dicetak pada: ' . now()->format('d M Y H:i') . ' WIB'
                );
                $sheet->getStyle('A' . $footerRow)->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);
            },
        ];
    }
}