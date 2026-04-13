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
    protected $province;
    protected $categoryId;
    protected $paymentMethod;
    protected $status;
    protected $orders;

    public function __construct(
        $startDate,
        $endDate,
        $province      = null,
        $categoryId    = null,
        $paymentMethod = null,
        $status        = null
    ) {
        $this->startDate     = $startDate;
        $this->endDate       = $endDate;
        $this->province      = $province;
        $this->categoryId    = $categoryId;
        $this->paymentMethod = $paymentMethod;
        $this->status        = $status;
    }

    public function collection()
    {
        $query = Order::with(['items.product.category', 'payment'])
            ->whereBetween('created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate   . ' 23:59:59',
            ])
            ->orderBy('created_at', 'asc');

        if ($this->province)      $query->where('province', $this->province);
        if ($this->status)        $query->where('status', $this->status);
        if ($this->paymentMethod) $query->whereHas('payment', fn($q) => $q->where('payment_type', $this->paymentMethod));
        if ($this->categoryId)    $query->whereHas('items.product', fn($q) => $q->where('category_id', $this->categoryId));

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

                // =====================
                // KOP SURAT
                // =====================
                $sheet->mergeCells('B1:L2');
                $sheet->setCellValue('B1', 'E-COMMERCE TSA');
                $sheet->getStyle('B1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 18],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                $sheet->mergeCells('B3:L3');
                $sheet->setCellValue('B3', 'Jl. Raya Nasional 12 No. 45 - Bandar Lampung');
                $sheet->getStyle('B3')->applyFromArray([
                    'font'      => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('B4:L4');
                $sheet->setCellValue('B4', 'Email: admin@ecommerce-tsa.com | Telp: 0822-1234-5678');
                $sheet->getStyle('B4')->applyFromArray([
                    'font'      => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A5:L5');
                $sheet->getStyle('A5:L5')->applyFromArray([
                    'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']]],
                ]);

                // =====================
                // JUDUL
                // =====================
                $sheet->mergeCells('A7:L7');
                $sheet->setCellValue('A7', 'LAPORAN PENJUALAN');
                $sheet->getStyle('A7')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 15],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->mergeCells('A8:L8');
                $sheet->setCellValue('A8',
                    'Periode: ' .
                    \Carbon\Carbon::parse($this->startDate)->format('d M Y') .
                    ' s/d ' .
                    \Carbon\Carbon::parse($this->endDate)->format('d M Y')
                );
                $sheet->getStyle('A8')->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Filter aktif
                $currentRow = 9;
                $filterParts = [];
                if ($this->province)      $filterParts[] = 'Provinsi: ' . $this->province;
                if ($this->categoryId)    $filterParts[] = 'Kategori ID: ' . $this->categoryId;
                if ($this->paymentMethod) $filterParts[] = 'Metode: ' . $this->paymentMethod;
                if ($this->status)        $filterParts[] = 'Status: ' . $this->status;

                if (!empty($filterParts)) {
                    $sheet->mergeCells('A9:L9');
                    $sheet->setCellValue('A9', 'Filter: ' . implode('  |  ', $filterParts));
                    $sheet->getStyle('A9')->applyFromArray([
                        'font'      => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '555555']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                    $currentRow = 10;
                }

                // =====================
                // RINGKASAN STATISTIK
                // =====================
                $statsStartRow = $currentRow + 1;

                // Hitung statistik — Total = Subtotal + Ongkir
                $totalRevenue   = $this->orders->sum(fn($o) => ($o->subtotal ?? 0) + ($o->shipping_cost ?? 0));
                $totalOrders    = $this->orders->count();
                $totalItemsSold = $this->orders->sum(fn($o) => $o->items->sum('quantity'));
                $avgOrderValue  = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 0) : 0;

                // Baris label statistik
                $statsLabelRow = $statsStartRow;
                $statsValueRow = $statsStartRow + 1;

                $statsData = [
                    'A' => ['label' => 'Total Pendapatan',    'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.')],
                    'D' => ['label' => 'Total Pesanan',       'value' => number_format($totalOrders)],
                    'G' => ['label' => 'Total Item Terjual',  'value' => number_format($totalItemsSold)],
                    'J' => ['label' => 'Rata-rata Pesanan',   'value' => 'Rp ' . number_format($avgOrderValue, 0, ',', '.')],
                ];

                foreach ($statsData as $col => $data) {
                    $endCol = chr(ord($col) + 2); // span 3 kolom
                    $sheet->mergeCells($col . $statsLabelRow . ':' . $endCol . $statsLabelRow);
                    $sheet->mergeCells($col . $statsValueRow . ':' . $endCol . $statsValueRow);
                    $sheet->setCellValue($col . $statsLabelRow, $data['label']);
                    $sheet->setCellValue($col . $statsValueRow, $data['value']);
                }

                // Style label
                $sheet->getStyle('A' . $statsLabelRow . ':L' . $statsLabelRow)->applyFromArray([
                    'font'      => ['size' => 9, 'color' => ['rgb' => '555555']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F7F4']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'b7dbc8']]],
                ]);

                // Style nilai
                $sheet->getStyle('A' . $statsValueRow . ':L' . $statsValueRow)->applyFromArray([
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
                    'D' => 'Provinsi',
                    'E' => 'Kategori',
                    'F' => 'Produk',
                    'G' => 'Qty',
                    'H' => 'Subtotal',
                    'I' => 'Ongkir',
                    'J' => 'Total',
                    'K' => 'Metode Bayar',
                    'L' => 'Status',
                ];

                foreach ($headers as $col => $label) {
                    $sheet->setCellValue($col . $headerRow, $label);
                }

                $sheet->getStyle('A' . $headerRow . ':L' . $headerRow)->applyFromArray([
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

                foreach ($this->orders as $order) {
                    $categories = $order->items
                        ->map(fn($item) => $item->product?->category?->name)
                        ->filter()->unique()->implode(', ') ?: '-';

                    $products = $order->items
                        ->map(fn($item) => ($item->product?->name ?? '-') . ' (x' . $item->quantity . ')')
                        ->implode(', ');

                    $qty   = $order->items->sum('quantity');
                    $total = ($order->subtotal ?? 0) + ($order->shipping_cost ?? 0);
                    $grandTotal += $total;

                    $paymentLabel = match($order->payment?->payment_type ?? '') {
                        'bank_transfer' => 'Transfer Bank',
                        'echannel'      => 'Mandiri E-Channel',
                        'cstore'        => 'Minimarket',
                        'gopay'         => 'GoPay',
                        'qris'          => 'QRIS',
                        'shopeepay'     => 'ShopeePay',
                        'credit_card'   => 'Kartu Kredit',
                        default         => ucfirst(str_replace('_', ' ', $order->payment?->payment_type ?? '-')),
                    };

                    $statusLabels = [
                        'pending'    => 'Menunggu',
                        'paid'       => 'Dibayar',
                        'processing' => 'Diproses',
                        'shipped'    => 'Dikirim',
                        'completed'  => 'Selesai',
                        'cancelled'  => 'Dibatalkan',
                    ];

                    $sheet->setCellValue('A' . $currentRow, $no++);
                    $sheet->setCellValue('B' . $currentRow, $order->order_number ?? '-');
                    $sheet->setCellValue('C' . $currentRow, $order->created_at->format('d/m/Y H:i'));
                    $sheet->setCellValue('D' . $currentRow, $order->province ?? '-');
                    $sheet->setCellValue('E' . $currentRow, $categories);
                    $sheet->setCellValue('F' . $currentRow, $products);
                    $sheet->setCellValue('G' . $currentRow, $qty);
                    $sheet->setCellValue('H' . $currentRow, 'Rp ' . number_format($order->subtotal ?? 0, 0, ',', '.'));
                    $sheet->setCellValue('I' . $currentRow, 'Rp ' . number_format($order->shipping_cost ?? 0, 0, ',', '.'));
                    $sheet->setCellValue('J' . $currentRow, 'Rp ' . number_format($total, 0, ',', '.'));
                    $sheet->setCellValue('K' . $currentRow, $paymentLabel);
                    $sheet->setCellValue('L' . $currentRow, $statusLabels[$order->status] ?? ucfirst($order->status));

                    // Zebra stripe
                    if ($no % 2 === 0) {
                        $sheet->getStyle('A' . $currentRow . ':L' . $currentRow)->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F7F4']],
                        ]);
                    }

                    $currentRow++;
                }

                // =====================
                // BORDER DATA
                // =====================
                $lastDataRow = $currentRow - 1;
                if ($lastDataRow >= $dataStartRow) {
                    $sheet->getStyle('A' . $dataStartRow . ':L' . $lastDataRow)->applyFromArray([
                        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);
                }

                // =====================
                // BARIS TOTAL
                // =====================
                $totalRow = $lastDataRow + 1;
                $sheet->mergeCells('A' . $totalRow . ':I' . $totalRow);
                $sheet->setCellValue('A' . $totalRow, 'TOTAL PENDAPATAN');
                $sheet->setCellValue('J' . $totalRow, 'Rp ' . number_format($grandTotal, 0, ',', '.'));
                $sheet->getStyle('A' . $totalRow . ':L' . $totalRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D8F3DC']],
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '2D6A4F']],
                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '2D6A4F']],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('J' . $totalRow)->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => '2D6A4F'], 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);

                // =====================
                // LEBAR KOLOM
                // =====================
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(18);
                $sheet->getColumnDimension('F')->setWidth(40);
                $sheet->getColumnDimension('G')->setWidth(7);
                $sheet->getColumnDimension('H')->setWidth(18);
                $sheet->getColumnDimension('I')->setWidth(16);
                $sheet->getColumnDimension('J')->setWidth(20);
                $sheet->getColumnDimension('K')->setWidth(20);
                $sheet->getColumnDimension('L')->setWidth(14);

                // Wrap text kolom produk
                if ($lastDataRow >= $dataStartRow) {
                    $sheet->getStyle('F' . $dataStartRow . ':F' . $lastDataRow)
                          ->getAlignment()->setWrapText(true);
                }

                // =====================
                // FOOTER
                // =====================
                $footerRow = $totalRow + 2;
                $sheet->mergeCells('A' . $footerRow . ':L' . $footerRow);
                $sheet->setCellValue('A' . $footerRow, 'Dicetak pada: ' . now()->format('d M Y H:i') . ' WIB');
                $sheet->getStyle('A' . $footerRow)->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);
            },
        ];
    }
}