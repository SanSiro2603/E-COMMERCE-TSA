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
    protected $status;
    protected $orders;

    public function __construct($startDate, $endDate, $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->status    = $status;
    }

    public function collection()
    {
        $query = Order::with(['user', 'items.product'])
            ->whereBetween('created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate   . ' 23:59:59',
            ])
            ->orderBy('created_at', 'asc');

        if ($this->status) $query->where('status', $this->status);

        $this->orders = $query->get();

        return collect([]);
    }

    public function drawings()
    {
        $logoPath = public_path('images/logo.png');
        if (!file_exists($logoPath)) return [];

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

                // Kolom A-M (13 kolom)
                // A=No, B=No.Pesanan, C=Tanggal, D=Pembeli, E=Email, F=No.Telp,
                // G=Provinsi, H=Kota/Kab, I=Alamat, J=Produk, K=Jumlah, L=Total, M=Status

                // =====================
                // KOP SURAT
                // =====================
                $sheet->mergeCells('B1:M2');
                $sheet->setCellValue('B1', 'E-COMMERCE TSA');
                $sheet->getStyle('B1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 18],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                $sheet->mergeCells('B3:M3');
                $sheet->setCellValue('B3', 'Jl. Raya Nasional 12 No. 45 - Bandar Lampung');
                $sheet->getStyle('B3')->applyFromArray([
                    'font'      => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('B4:M4');
                $sheet->setCellValue('B4', 'Email: admin@ecommerce-tsa.com | Telp: 0822-1234-5678');
                $sheet->getStyle('B4')->applyFromArray([
                    'font'      => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A5:M5');
                $sheet->getStyle('A5:M5')->applyFromArray([
                    'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']]],
                ]);

                // =====================
                // JUDUL
                // =====================
                $sheet->mergeCells('A7:M7');
                $sheet->setCellValue('A7', 'LAPORAN PENJUALAN');
                $sheet->getStyle('A7')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 15],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A8:M8');
                $sheet->setCellValue('A8',
                    'Periode: ' .
                    \Carbon\Carbon::parse($this->startDate)->format('d M Y') .
                    ' s/d ' .
                    \Carbon\Carbon::parse($this->endDate)->format('d M Y')
                );
                $sheet->getStyle('A8')->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Filter status
                $infoRow = 9;
                if ($this->status) {
                    $statusLabels = [
                        'pending'    => 'Menunggu Pembayaran',
                        'paid'       => 'Sudah Dibayar',
                        'processing' => 'Diproses',
                        'shipped'    => 'Dikirim',
                        'completed'  => 'Selesai',
                        'cancelled'  => 'Dibatalkan',
                    ];
                    $sheet->mergeCells('A9:M9');
                    $sheet->setCellValue('A9', 'Filter Status: ' . ($statusLabels[$this->status] ?? ucfirst($this->status)));
                    $sheet->getStyle('A9')->applyFromArray([
                        'font'      => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '555555']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                    $infoRow = 10;
                }

                // =====================
                // RINGKASAN STATISTIK
                // =====================
                $statsLabelRow = $infoRow + 1;
                $statsValueRow = $infoRow + 2;

                $totalRevenue   = $this->orders->sum('grand_total');
                $totalOrders    = $this->orders->count();
                $totalItemsSold = $this->orders->sum(fn($o) => $o->items->sum('quantity'));
                $avgOrderValue  = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 0) : 0;

                // 4 blok statistik span A-M
                $statsData = [
                    'A' => ['label' => 'Total Pendapatan',   'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),  'end' => 'C'],
                    'D' => ['label' => 'Total Pesanan',      'value' => number_format($totalOrders),                         'end' => 'F'],
                    'G' => ['label' => 'Total Item Terjual', 'value' => number_format($totalItemsSold),                      'end' => 'I'],
                    'J' => ['label' => 'Rata-rata Pesanan',  'value' => 'Rp ' . number_format($avgOrderValue, 0, ',', '.'),  'end' => 'M'],
                ];

                foreach ($statsData as $startCol => $data) {
                    $sheet->mergeCells($startCol . $statsLabelRow . ':' . $data['end'] . $statsLabelRow);
                    $sheet->mergeCells($startCol . $statsValueRow . ':' . $data['end'] . $statsValueRow);
                    $sheet->setCellValue($startCol . $statsLabelRow, $data['label']);
                    $sheet->setCellValue($startCol . $statsValueRow, $data['value']);
                }

                $sheet->getStyle('A' . $statsLabelRow . ':M' . $statsLabelRow)->applyFromArray([
                    'font'      => ['size' => 9, 'color' => ['rgb' => '555555']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F7F4']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'b7dbc8']]],
                ]);
                $sheet->getStyle('A' . $statsValueRow . ':M' . $statsValueRow)->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '2D6A4F']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F7F4']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'b7dbc8']]],
                ]);
                $sheet->getRowDimension($statsValueRow)->setRowHeight(20);

                // =====================
                // HEADER TABEL
                // =====================
                $headerRow = $statsValueRow + 2;

                $headers = [
                    'A' => 'No.',
                    'B' => 'No. Pesanan',
                    'C' => 'Tanggal',
                    'D' => 'Pembeli',
                    'E' => 'Email',
                    'F' => 'No. Telp',
                    'G' => 'Provinsi',
                    'H' => 'Kota/Kab.',
                    'I' => 'Alamat Lengkap',
                    'J' => 'Produk',
                    'K' => 'Jumlah',
                    'L' => 'Total',
                    'M' => 'Status',
                ];

                foreach ($headers as $col => $label) {
                    $sheet->setCellValue($col . $headerRow, $label);
                }

                $sheet->getStyle('A' . $headerRow . ':M' . $headerRow)->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2D6A4F']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);
                $sheet->getRowDimension($headerRow)->setRowHeight(22);

                // =====================
                // ISI DATA
                // =====================
                $dataStartRow = $headerRow + 1;
                $currentRow   = $dataStartRow;
                $no           = 1;
                $grandTotal   = 0;

                $statusLabels = [
                    'pending'    => 'Menunggu',
                    'paid'       => 'Dibayar',
                    'processing' => 'Diproses',
                    'shipped'    => 'Dikirim',
                    'completed'  => 'Selesai',
                    'cancelled'  => 'Dibatalkan',
                ];

                foreach ($this->orders as $order) {
                    $products = $order->items
                        ->map(fn($item) => ($item->product?->name ?? '-') . ' (x' . $item->quantity . ')')
                        ->implode(', ');
                    $qty = $order->items->sum('quantity');
                    $grandTotal += $order->grand_total ?? 0;

                    $sheet->setCellValue('A' . $currentRow, $no++);
                    $sheet->setCellValue('B' . $currentRow, $order->order_number);
                    $sheet->setCellValue('C' . $currentRow, $order->created_at->format('d/m/Y'));
                    $sheet->setCellValue('D' . $currentRow, $order->address?->recipient_name ?? $order->user->name);
                    $sheet->setCellValue('E' . $currentRow, $order->address?->province_name ?? '-');
                    $sheet->setCellValue('F' . $currentRow, ($order->address ? $order->address->city_type . ' ' . $order->address->city_name : '-'));
                    $sheet->setCellValue('G' . $currentRow, $order->address?->full_address ?? '-');
                    $sheet->setCellValue('H' . $currentRow, $order->address?->recipient_phone ?? '-');
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
                // BORDER DATA
                // =====================
                $lastDataRow = $currentRow - 1;
                if ($lastDataRow >= $dataStartRow) {
                    $sheet->getStyle('A' . $dataStartRow . ':M' . $lastDataRow)->applyFromArray([
                        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);
                }

                // =====================
                // BARIS TOTAL
                // =====================
                $totalRow = $lastDataRow + 1;
                $sheet->mergeCells('A' . $totalRow . ':K' . $totalRow);
                $sheet->setCellValue('A' . $totalRow, 'TOTAL PENDAPATAN');
                $sheet->setCellValue('L' . $totalRow, 'Rp ' . number_format($grandTotal, 0, ',', '.'));
                $sheet->getStyle('A' . $totalRow . ':M' . $totalRow)->applyFromArray([
                    'font'    => ['bold' => true, 'size' => 10],
                    'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D8F3DC']],
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '2D6A4F']],
                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '2D6A4F']],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('L' . $totalRow)->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => '2D6A4F'], 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);

                // =====================
                // LEBAR KOLOM
                // =====================
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(22);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(22);
                $sheet->getColumnDimension('E')->setWidth(28);
                $sheet->getColumnDimension('F')->setWidth(18);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(38);
                $sheet->getColumnDimension('J')->setWidth(42);
                $sheet->getColumnDimension('K')->setWidth(10);
                $sheet->getColumnDimension('L')->setWidth(22);
                $sheet->getColumnDimension('M')->setWidth(14);

                // Wrap text kolom alamat & produk
                if ($lastDataRow >= $dataStartRow) {
                    $sheet->getStyle('I' . $dataStartRow . ':I' . $lastDataRow)->getAlignment()->setWrapText(true);
                    $sheet->getStyle('J' . $dataStartRow . ':J' . $lastDataRow)->getAlignment()->setWrapText(true);
                }

                // =====================
                // FOOTER
                // =====================
                $footerRow = $totalRow + 2;
                $sheet->mergeCells('A' . $footerRow . ':M' . $footerRow);
                $sheet->setCellValue('A' . $footerRow, 'Dicetak pada: ' . now()->format('d M Y H:i') . ' WIB');
                $sheet->getStyle('A' . $footerRow)->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);
            },
        ];
    }
}