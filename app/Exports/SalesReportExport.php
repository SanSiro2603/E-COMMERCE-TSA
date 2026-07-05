<?php

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

    protected array $validStatuses = ['paid', 'processing', 'shipped', 'completed'];

    public function __construct($startDate, $endDate, $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->status    = $status;
    }

    public function collection()
    {
        $query = Order::with(['user', 'items.product', 'address', 'shippingSnapshot'])
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
        $logoPath = collect([
            public_path('images/logo-header.png'),
            public_path('images/logo header.png'),
            public_path('images/logo.png'),
        ])->first(fn($path) => file_exists($path));

        if (!$logoPath || !file_exists($logoPath)) return [];

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

                $infoRow = 9;
                $statusLabels = [
                    'pending'    => 'Menunggu Pembayaran',
                    'paid'       => 'Sudah Dibayar',
                    'processing' => 'Diproses',
                    'shipped'    => 'Dikirim',
                    'completed'  => 'Selesai',
                    'cancelled'  => 'Dibatalkan',
                ];

                if ($this->status) {
                    $sheet->mergeCells('A9:M9');
                    $sheet->setCellValue('A9', 'Filter Status: ' . ($statusLabels[$this->status] ?? ucfirst($this->status)));
                    $sheet->getStyle('A9')->applyFromArray([
                        'font'      => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '555555']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                    $infoRow = 10;
                }

                if ($this->status && !in_array($this->status, $this->validStatuses)) {
                    $sheet->mergeCells('A' . $infoRow . ':M' . $infoRow);
                    $sheet->setCellValue(
                        'A' . $infoRow,
                        'Catatan: Pesanan dengan status "' . ($statusLabels[$this->status] ?? $this->status) . '" tetap ditampilkan di tabel untuk analisis, tetapi tidak dihitung pada kartu statistik penjualan karena belum menjadi transaksi valid.'
                    );
                    $sheet->getStyle('A' . $infoRow)->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '92400E']],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBEB']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FACC15']]],
                    ]);
                    $infoRow++;
                }

                $statsLabelRow = $infoRow + 1;
                $statsValueRow = $infoRow + 2;

                $statsOrders = $this->orders->filter(fn($o) => in_array($o->status, $this->validStatuses));

                $totalRevenue   = $statsOrders->sum('grand_total');
                $totalOrders    = $statsOrders->count();
                $totalItemsSold = $statsOrders->sum(fn($o) => $o->items->sum('quantity'));
                $avgOrderValue  = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 0) : 0;

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

                $statusColors = [
                    'pending'    => ['bg' => 'FCD34D', 'font' => '78350F'],
                    'paid'       => ['bg' => '3B82F6', 'font' => 'FFFFFF'],
                    'processing' => ['bg' => 'A855F7', 'font' => 'FFFFFF'],
                    'shipped'    => ['bg' => '6366F1', 'font' => 'FFFFFF'],
                    'completed'  => ['bg' => '10B981', 'font' => 'FFFFFF'],
                    'cancelled'  => ['bg' => 'EF4444', 'font' => 'FFFFFF'],
                ];

                foreach ($this->orders as $order) {
                    $products = $order->items
                        ->map(fn($item) => $item->display_name . ' (x' . $item->quantity . ')' . ($item->product ? '' : ' - Produk sudah dihapus dari katalog'))
                        ->implode(', ');
                    $qty = $order->items->sum('quantity');

                    if (in_array($order->status, $this->validStatuses)) {
                        $grandTotal += $order->grand_total ?? 0;
                    }

                    $sheet->setCellValue('A' . $currentRow, $no);
                    $sheet->setCellValue('B' . $currentRow, $order->order_number ?? '-');
                    $sheet->setCellValue('C' . $currentRow, $order->created_at->format('d/m/Y H:i'));
                    $sheet->setCellValue('D' . $currentRow, $order->display_shipping_recipient_name ?? $order->user?->name ?? '-');
                    $sheet->setCellValue('E' . $currentRow, $order->user?->email ?? '-');
                    $sheet->setCellValue('F' . $currentRow, $order->display_shipping_recipient_phone ?? '-');
                    $sheet->setCellValue('G' . $currentRow, $order->display_shipping_province_name ?? '-');
                    $sheet->setCellValue('H' . $currentRow, trim(($order->display_shipping_city_type ?? '') . ' ' . ($order->display_shipping_city_name ?? '')) ?: '-');
                    $sheet->setCellValue('I' . $currentRow, $order->display_shipping_full_address ?? '-');
                    $sheet->setCellValue('J' . $currentRow, $products);
                    $sheet->setCellValue('K' . $currentRow, $qty);
                    $sheet->setCellValue('L' . $currentRow, 'Rp ' . number_format($order->grand_total ?? 0, 0, ',', '.'));
                    $sheet->setCellValue('M' . $currentRow, $statusLabels[$order->status] ?? ucfirst($order->status));

                    $sc = $statusColors[$order->status] ?? ['bg' => '9CA3AF', 'font' => 'FFFFFF'];
                    $sheet->getStyle('M' . $currentRow)->applyFromArray([
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $sc['bg']]],
                        'font'      => ['color' => ['rgb' => $sc['font']], 'bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    if ($no % 2 !== 0) {
                        $sheet->getStyle('A' . $currentRow . ':L' . $currentRow)->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F7F4']],
                        ]);
                    }

                    $no++;
                    $currentRow++;
                }

                $lastDataRow = $currentRow - 1;
                if ($lastDataRow >= $dataStartRow) {
                    $sheet->getStyle('A' . $dataStartRow . ':M' . $lastDataRow)->applyFromArray([
                        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);
                }

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

                if ($lastDataRow >= $dataStartRow) {
                    $sheet->getStyle('I' . $dataStartRow . ':I' . $lastDataRow)->getAlignment()->setWrapText(true);
                    $sheet->getStyle('J' . $dataStartRow . ':J' . $lastDataRow)->getAlignment()->setWrapText(true);
                }

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
