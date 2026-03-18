<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    WithColumnWidths,
    WithEvents
{
    public function __construct(
        protected ?string $startDate = null,
        protected ?string $endDate = null,
        protected ?string $status = null,
        protected ?int $regionId = null,
    ) {}

    // ── Query data ─────────────────────────────────────────
    public function query()
    {
        return Booking::with([
            'requester.region',
            'requester.department',
            'vehicle.region',
            'driver.user',
            'approvals.approver',
            'fuelLog',
        ])
            ->when(
                $this->startDate,
                fn($q) =>
                $q->where('departure_at', '>=', $this->startDate . ' 00:00:00')
            )
            ->when(
                $this->endDate,
                fn($q) =>
                $q->where('departure_at', '<=', $this->endDate . ' 23:59:59')
            )
            ->when(
                $this->status,
                fn($q) =>
                $q->where('status', $this->status)
            )
            ->when(
                $this->regionId,
                fn($q) =>
                $q->whereHas(
                    'vehicle',
                    fn($q2) =>
                    $q2->where('region_id', $this->regionId)
                )
            )
            ->orderBy('departure_at', 'desc');
    }

    // ── Judul sheet ────────────────────────────────────────
    public function title(): string
    {
        return 'Laporan Pemesanan';
    }

    // ── Header kolom ───────────────────────────────────────
    public function headings(): array
    {
        return [
            'No',
            'Kode Booking',
            'Tanggal Dibuat',
            'Pemohon',
            'Departemen',
            'Region',
            'Kendaraan',
            'Plat Nomor',
            'Jenis',
            'Driver',
            'Tujuan Perjalanan',
            'Destinasi',
            'Tanggal Berangkat',
            'Estimasi Kembali',
            'Jml. Penumpang',
            'Odometer Awal (km)',
            'Odometer Akhir (km)',
            'Jarak Tempuh (km)',
            'Total BBM (liter)',
            'Biaya BBM (Rp)',
            'Approval Level 1',
            'Status L1',
            'Approval Level 2',
            'Status L2',
            'Status Booking',
            'Keterangan',
        ];
    }

    // ── Mapping data per baris ─────────────────────────────
    public function map($booking): array
    {
        static $no = 0;
        $no++;

        $approval1 = $booking->approvals->firstWhere('level', 1);
        $approval2 = $booking->approvals->firstWhere('level', 2);

        $totalFuelLiter = $booking->fuelLog->sum('liters');
        $totalFuelCost  = $booking->fuelLog->sum('total_cost');

        $distance = ($booking->odometer_end && $booking->odometer_start)
            ? $booking->odometer_end - $booking->odometer_start
            : '-';

        $statusLabel = match ($booking->status) {
            'pending'    => 'Menunggu',
            'in_review'  => 'Direview',
            'approved'   => 'Disetujui',
            'rejected'   => 'Ditolak',
            'in_use'     => 'Sedang Digunakan',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            default      => $booking->status,
        };

        $approvalStatusLabel = fn($approval) => match ($approval?->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'waiting'  => 'Menunggu',
            default    => '-',
        };

        return [
            $no,
            $booking->booking_code,
            $booking->created_at->format('d/m/Y H:i'),
            $booking->requester->name,
            $booking->requester->department?->name ?? '-',
            $booking->requester->region?->name ?? '-',
            $booking->vehicle->brand . ' ' . $booking->vehicle->model,
            $booking->vehicle->plate_number,
            $booking->vehicle->type === 'passenger' ? 'Angkutan Orang' : 'Angkutan Barang',
            $booking->driver->user->name,
            $booking->purpose,
            $booking->destination,
            $booking->departure_at->format('d/m/Y H:i'),
            $booking->return_at->format('d/m/Y H:i'),
            $booking->passenger_count,
            $booking->odometer_start ?? '-',
            $booking->odometer_end ?? '-',
            $distance,
            $totalFuelLiter > 0 ? $totalFuelLiter : '-',
            $totalFuelCost > 0 ? $totalFuelCost : '-',
            $approval1?->approver->name ?? '-',
            $approvalStatusLabel($approval1),
            $approval2?->approver->name ?? '-',
            $approvalStatusLabel($approval2),
            $statusLabel,
            $booking->cancellation_reason ?? $booking->description ?? '-',
        ];
    }

    // ── Lebar kolom ────────────────────────────────────────
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 22,  // Kode Booking
            'C' => 18,  // Tanggal Dibuat
            'D' => 22,  // Pemohon
            'E' => 22,  // Departemen
            'F' => 26,  // Region
            'G' => 24,  // Kendaraan
            'H' => 14,  // Plat Nomor
            'I' => 18,  // Jenis
            'J' => 22,  // Driver
            'K' => 30,  // Tujuan
            'L' => 28,  // Destinasi
            'M' => 18,  // Tgl Berangkat
            'N' => 18,  // Estimasi Kembali
            'O' => 16,  // Jml Penumpang
            'P' => 18,  // Odometer Awal
            'Q' => 18,  // Odometer Akhir
            'R' => 18,  // Jarak Tempuh
            'S' => 18,  // Total BBM
            'T' => 20,  // Biaya BBM
            'U' => 22,  // Approver L1
            'V' => 14,  // Status L1
            'W' => 22,  // Approver L2
            'X' => 14,  // Status L2
            'Y' => 18,  // Status Booking
            'Z' => 30,  // Keterangan
        ];
    }

    // ── Styling ────────────────────────────────────────────
    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row
            1 => [
                'font' => [
                    'bold'  => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size'  => 11,
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E40AF'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                    'wrapText'   => true,
                ],
            ],
        ];
    }

    // ── Events untuk styling lanjutan ─────────────────────
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet     = $event->sheet->getDelegate();
                $lastRow   = $sheet->getHighestRow();
                $lastCol   = 'Z';

                // ── Freeze pane header ──────────────────
                $sheet->freezePane('A2');

                // ── Row header height ───────────────────
                $sheet->getRowDimension(1)->setRowHeight(35);

                // ── Border semua sel data ───────────────
                $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['argb' => 'FFE2E8F0'],
                        ],
                    ],
                ]);

                // ── Border luar lebih tebal ─────────────
                $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color'       => ['argb' => 'FF1E40AF'],
                        ],
                    ],
                ]);

                // ── Alternating row color ───────────────
                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFF8FAFC'],
                            ],
                        ]);
                    }
                }

                // ── Warna kolom status booking ──────────
                for ($row = 2; $row <= $lastRow; $row++) {
                    $status = $sheet->getCell("Y{$row}")->getValue();
                    $color  = match ($status) {
                        'Selesai'          => ['bg' => 'FFD1FAE5', 'font' => 'FF065F46'],
                        'Disetujui'        => ['bg' => 'FFDBEAFE', 'font' => 'FF1E40AF'],
                        'Ditolak'          => ['bg' => 'FFFEE2E2', 'font' => 'FF991B1B'],
                        'Sedang Digunakan' => ['bg' => 'FFEDE9FE', 'font' => 'FF5B21B6'],
                        'Dibatalkan'       => ['bg' => 'FFF1F5F9', 'font' => 'FF475569'],
                        default            => ['bg' => 'FFFFF7ED', 'font' => 'FF92400E'],
                    };

                    $sheet->getStyle("Y{$row}")->applyFromArray([
                        'fill' => [
                            'fillType'   => Fill::FILL_SOLID,
                            'startColor' => ['argb' => $color['bg']],
                        ],
                        'font' => [
                            'bold'  => true,
                            'color' => ['argb' => $color['font']],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                }

                // ── Center align kolom tertentu ─────────
                $centerCols = ['A', 'B', 'C', 'H', 'I', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'V', 'X', 'Y'];
                foreach ($centerCols as $col) {
                    $sheet->getStyle("{$col}2:{$col}{$lastRow}")->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                }

                // ── Format angka kolom biaya BBM ────────
                $sheet->getStyle("T2:T{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"Rp "#,##0');

                // ── Vertical align semua baris ──────────
                $sheet->getStyle("A2:{$lastCol}{$lastRow}")->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // ── Auto row height untuk data ──────────
                for ($row = 2; $row <= $lastRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(22);
                }

                // ── Sheet title di atas header ──────────
                $sheet->insertNewRowBefore(1, 3);

                // Judul laporan
                $sheet->setCellValue('A1', 'LAPORAN PEMESANAN KENDARAAN');
                $sheet->setCellValue('A2', 'PT Nikel Mining Indonesia');
                $sheet->setCellValue('A3', 'Digenerate: ' . now()->format('d/m/Y H:i'));

                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->mergeCells("A3:{$lastCol}3");

                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF1E40AF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['size' => 11, 'color' => ['argb' => 'FF475569']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A3')->applyFromArray([
                    'font'      => ['size' => 10, 'italic' => true, 'color' => ['argb' => 'FF94A3B8']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(28);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(18);
            },
        ];
    }
}
