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
            ->with(['user', 'address', 'items'])
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

        // Return collection kosong karena kita akan isi manual di AfterSheet
        return collect([]);
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
                // KOP SURAT (Baris 1-4)
                // =====================
                
                // Merge cells untuk nama perusahaan
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

                // Alamat dan kontak
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

                // Garis pemisah
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
                // JUDUL LAPORAN (Baris 7-9)
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

                // Periode
                $sheet->mergeCells('A8:L8');
                $periodText = 'Periode: ' . 
                    \Carbon\Carbon::parse($this->startDate)->format('d M Y') . 
                    ' - ' . 
                    \Carbon\Carbon::parse($this->endDate)->format('d M Y');
                $sheet->setCellValue('A8', $periodText);
                $sheet->getStyle('A8')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Total Pendapatan
                $sheet->mergeCells('A9:L9');
                $sheet->setCellValue('A9', 'Total Pendapatan: Rp ' . number_format($this->totalRevenue, 0, ',', '.'));
                $sheet->getStyle('A9')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // =====================
                // HEADER TABEL (Baris 11)
                // =====================
                
                $headerRow = 11;
                $headers = ['No.', 'No. Pesanan', 'Tanggal', 'Pembeli', 'Provinsi', 'Kota/Kabupaten', 'Alamat Lengkap', 'No. Telp', 'Metode Pembayaran', 'Status Pembayaran', 'Jumlah Item', 'Total'];
                $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
                
                foreach($headers as $index => $header) {
                    $sheet->setCellValue($columns[$index] . $headerRow, $header);
                }

                // Style header
                $sheet->getStyle('A' . $headerRow . ':L' . $headerRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F3F3'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'BBBBBB'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // =====================
                // ISI DATA (Mulai Baris 12)
                // =====================
                
                $dataStartRow = 12;
                $currentRow = $dataStartRow;
                $no = 1;

                foreach($this->orders as $order) {
                    // Hitung total item dalam order
                    $totalItems = $order->items->sum('quantity');
                    
                    // Tentukan metode pembayaran
                    $paymentMethod = $order->payment_method ?? 'Transfer Bank';
                    if (stripos($paymentMethod, 'cod') !== false) {
                        $paymentMethod = 'COD (Cash on Delivery)';
                    } elseif (stripos($paymentMethod, 'transfer') !== false) {
                        $paymentMethod = 'Transfer Bank';
                    } elseif (stripos($paymentMethod, 'ewallet') !== false || stripos($paymentMethod, 'wallet') !== false) {
                        $paymentMethod = 'E-Wallet';
                    }
                    
                    // Status pembayaran
                    $paymentStatus = $order->payment_status ?? 'Lunas';
                    if ($paymentStatus == 'paid' || $paymentStatus == 'completed') {
                        $paymentStatus = 'Lunas';
                    } elseif ($paymentStatus == 'pending') {
                        $paymentStatus = 'Pending';
                    }
                    
                    $sheet->setCellValue('A' . $currentRow, $no++);
                    $sheet->setCellValue('B' . $currentRow, $order->order_number);
                    $sheet->setCellValue('C' . $currentRow, $order->created_at->format('d/m/Y'));
                    $sheet->setCellValue('D' . $currentRow, $order->recipient_name ?? $order->user->name);
                    $sheet->setCellValue('E' . $currentRow, $order->province ?? '-');
                    $sheet->setCellValue('F' . $currentRow, $order->city ?? '-');
                    $sheet->setCellValue('G' . $currentRow, $order->address?->full_address ?? $order->shipping_address ?? '-');
                    $sheet->setCellValue('H' . $currentRow, $order->recipient_phone ?? '-');
                    $sheet->setCellValue('I' . $currentRow, $paymentMethod);
                    $sheet->setCellValue('J' . $currentRow, $paymentStatus);
                    $sheet->setCellValue('K' . $currentRow, $totalItems);
                    $sheet->setCellValue('L' . $currentRow, 'Rp ' . number_format($order->grand_total, 0, ',', '.'));
                    
                    $currentRow++;
                }

                $lastDataRow = $currentRow - 1;

                // Style untuk data
                if($lastDataRow >= $dataStartRow) {
                    $sheet->getStyle('A' . $dataStartRow . ':L' . $lastDataRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'BBBBBB'],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_TOP,
                        ],
                    ]);

                    // Zebra striping untuk baris genap
                    for($row = $dataStartRow; $row <= $lastDataRow; $row++) {
                        if(($row - $dataStartRow) % 2 == 1) {
                            $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'FAFAFA'],
                                ],
                            ]);
                        }
                    }

                    // Text wrapping untuk alamat
                    $sheet->getStyle('G' . $dataStartRow . ':G' . $lastDataRow)
                        ->getAlignment()->setWrapText(true);
                    
                    // Center alignment untuk kolom tertentu
                    $sheet->getStyle('I' . $dataStartRow . ':K' . $lastDataRow)
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // =====================
                // SET COLUMN WIDTHS
                // =====================
                
                $sheet->getColumnDimension('A')->setWidth(6);   // No
                $sheet->getColumnDimension('B')->setWidth(22);  // No Pesanan
                $sheet->getColumnDimension('C')->setWidth(13);  // Tanggal
                $sheet->getColumnDimension('D')->setWidth(18);  // Pembeli
                $sheet->getColumnDimension('E')->setWidth(15);  // Provinsi
                $sheet->getColumnDimension('F')->setWidth(20);  // Kota
                $sheet->getColumnDimension('G')->setWidth(35);  // Alamat
                $sheet->getColumnDimension('H')->setWidth(15);  // No Telp
                $sheet->getColumnDimension('I')->setWidth(20);  // Metode Pembayaran
                $sheet->getColumnDimension('J')->setWidth(18);  // Status Pembayaran
                $sheet->getColumnDimension('K')->setWidth(12);  // Jumlah Item
                $sheet->getColumnDimension('L')->setWidth(18);  // Total

                // =====================
                // SET ROW HEIGHTS
                // =====================
                
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(10);
                $sheet->getRowDimension(3)->setRowHeight(18);
                $sheet->getRowDimension(4)->setRowHeight(18);
                $sheet->getRowDimension(5)->setRowHeight(5);
                $sheet->getRowDimension(7)->setRowHeight(22);
                $sheet->getRowDimension(8)->setRowHeight(18);
                $sheet->getRowDimension(9)->setRowHeight(18);
                $sheet->getRowDimension($headerRow)->setRowHeight(22);

                // Set minimal height untuk data rows
                for($row = $dataStartRow; $row <= $lastDataRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(-1); // Auto height
                }
            },
        ];
    }
}