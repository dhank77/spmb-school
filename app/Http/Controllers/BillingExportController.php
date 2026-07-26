<?php

namespace App\Http\Controllers;

use App\Exports\BillingExport;
use App\Models\AdmissionWave;
use App\Models\PaymentOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BillingExportController extends Controller
{
    /**
     * Resolve billing amounts for the authenticated user.
     *
     * @return array{baseFee: int, uniqueCode: int, isPaid: bool}
     */
    private function resolveBillingData(): array
    {
        $user = Auth::user();
        $isPaid = $user->isPaid();

        if ($isPaid) {
            $order = PaymentOrder::where('user_id', $user->id)
                ->where('status', 'success')
                ->latest()
                ->first();

            if ($order) {
                $amount = $order->amount;
                $rem10000 = $amount % 10000;
                $uniqueCode = ($rem10000 >= 4000 && $rem10000 <= 4999) ? $rem10000 : ($amount % 1000);
                $baseFee = $amount - $uniqueCode;

                return compact('baseFee', 'uniqueCode', 'isPaid');
            }
        }

        // Fallback: active wave base fee + stored pending order unique code, or defaults
        $baseFee = 250000;
        $pendingOrder = PaymentOrder::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($pendingOrder) {
            $amount = $pendingOrder->amount;
            $rem10000 = $amount % 10000;
            $uniqueCode = ($rem10000 >= 4000 && $rem10000 <= 4999) ? $rem10000 : ($amount % 1000);
            $baseFee = $amount - $uniqueCode;
        } else {
            $activeWave = AdmissionWave::where('status', 'active')->first();
            if ($activeWave) {
                $baseFee = $activeWave->registration_cost;
            }
            $uniqueCode = rand(4000, 4999);
        }

        return compact('baseFee', 'uniqueCode', 'isPaid');
    }

    /**
     * Download billing invoice as PDF.
     */
    public function downloadPdf(Request $request): Response
    {
        $user = Auth::user();
        ['baseFee' => $baseFee, 'uniqueCode' => $uniqueCode, 'isPaid' => $isPaid] = $this->resolveBillingData();

        $pdf = Pdf::loadView('exports.billing-pdf', compact('user', 'baseFee', 'uniqueCode', 'isPaid'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'tagihan-spmb-'.str_pad($user->id, 4, '0', STR_PAD_LEFT).'-'.date('Ymd').'.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Show a preview of the Excel data before downloading.
     */
    public function previewExcel(Request $request): View
    {
        $user = Auth::user();
        ['baseFee' => $baseFee, 'uniqueCode' => $uniqueCode, 'isPaid' => $isPaid] = $this->resolveBillingData();

        return view('exports.billing-excel-preview', compact('user', 'baseFee', 'uniqueCode', 'isPaid'));
    }

    /**
     * Download billing invoice as Excel.
     */
    public function downloadExcel(Request $request): BinaryFileResponse
    {
        $user = Auth::user();
        ['baseFee' => $baseFee, 'uniqueCode' => $uniqueCode, 'isPaid' => $isPaid] = $this->resolveBillingData();

        $filename = 'tagihan-spmb-'.str_pad($user->id, 4, '0', STR_PAD_LEFT).'-'.date('Ymd').'.xlsx';

        return Excel::download(new BillingExport($user, $baseFee, $uniqueCode, $isPaid), $filename);
    }
}
