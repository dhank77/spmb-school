<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BillingExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithTitle
{
    public function __construct(
        private readonly User $user,
        private readonly int $baseFee,
        private readonly int $uniqueCode,
        private readonly bool $isPaid,
    ) {}

    public function title(): string
    {
        return 'Tagihan Pendaftaran';
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['Keterangan', 'Nilai'];
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function array(): array
    {
        $total = $this->baseFee + $this->uniqueCode;
        $invNumber = 'INV/SPMB/'.date('Y').'/'.str_pad($this->user->id, 4, '0', STR_PAD_LEFT);

        return [
            ['Nomor Tagihan', $invNumber],
            ['Nama Pendaftar', $this->user->name],
            ['Email', $this->user->email],
            ['No. Pendaftaran', $this->user->registration_number ?? '-'],
            ['Tanggal Terbit', now()->format('d/m/Y')],
            ['Status', $this->isPaid ? 'LUNAS' : 'BELUM BAYAR'],
            ['Metode Pembayaran', $this->isPaid ? ($this->user->payment_method ?? '-') : '-'],
            ['', ''],
            ['Biaya Pendaftaran', 'Rp '.number_format($this->baseFee, 0, ',', '.')],
            ['Kode Unik & Fee', 'Rp '.number_format($this->uniqueCode, 0, ',', '.')],
            ['Total '.($this->isPaid ? 'Dibayarkan' : 'Pembayaran'), 'Rp '.number_format($total, 0, ',', '.')],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function styles(Worksheet $sheet): array
    {
        // Title row (headings)
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6750A4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Total row (row 12 = row index 11 + 1 heading + 1)
        $totalRow = count($this->array()) + 1;
        $sheet->getStyle("A{$totalRow}:B{$totalRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EADDFF']],
        ]);

        // Border all cells
        $lastRow = $totalRow;
        $sheet->getStyle("A1:B{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CAC4D0'],
                ],
            ],
        ]);

        // Column A bold for labels
        $sheet->getStyle('A1:A'.$lastRow)->getFont()->setBold(true);

        return [];
    }
}
