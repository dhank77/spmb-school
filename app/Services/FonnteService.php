<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    private string $apiKey;

    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.fonnte.api_key');
        $this->baseUrl = config('services.fonnte.base_url', 'https://api.fonnte.com/send');
    }

    /**
     * Send a WhatsApp message via Fonnte API.
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function send(string $target, string $message, array $params = []): array
    {
        $payload = array_merge([
            'target' => $target,
            'message' => $message,
            'countryCode' => '62',
        ], $params);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->asForm()->post($this->baseUrl, $payload);

            $result = $response->json() ?? [];

            Log::info('Fonnte WA sent', [
                'target' => $target,
                'status' => $result['status'] ?? null,
                'detail' => $result['detail'] ?? null,
            ]);

            return $result;
        } catch (\Throwable $e) {
            Log::error('Fonnte WA send failed', [
                'target' => $target,
                'error' => $e->getMessage(),
            ]);

            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }

    /**
     * Send a registration billing notification to the given WhatsApp number.
     */
    public function sendRegistrationBilling(
        string $whatsappNumber,
        string $studentName,
        string $registrationNumber,
        int $baseFee,
        int $uniqueCode,
    ): array {
        $total = $baseFee + $uniqueCode;
        $formattedBaseFee = 'Rp '.number_format($baseFee, 0, ',', '.');
        $formattedUniqueCode = 'Rp '.number_format($uniqueCode, 0, ',', '.');
        $formattedTotal = 'Rp '.number_format($total, 0, ',', '.');

        $message = "🎓 *TAGIHAN PENDAFTARAN SISWA BARU*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "Halo, *{$studentName}*! 👋\n\n";
        $message .= "Pendaftaran Anda di *Hitech School* telah berhasil diterima. Berikut detail tagihan pembayaran biaya pendaftaran:\n\n";
        $message .= "📋 *Detail Tagihan*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "No. Pendaftaran  : *{$registrationNumber}*\n";
        $message .= "Nama Siswa       : *{$studentName}*\n\n";
        $message .= "Biaya Pendaftaran: *{$formattedBaseFee}*\n";
        $message .= "Kode Unik & Fee  : *{$formattedUniqueCode}*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "Total Tagihan    : *{$formattedTotal}*\n\n";
        $message .= "💳 *Cara Pembayaran*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "Silakan login ke akun Anda dan pilih metode pembayaran yang tersedia (Transfer Bank, QRIS, GoPay, dll.).\n\n";
        $message .= "🔗 *Link Pembayaran*\n";
        $message .= route('dashboard')."\n\n";
        $message .= "⏰ Segera lakukan pembayaran untuk memproses pendaftaran Anda lebih lanjut.\n\n";
        $message .= "Terima kasih telah mendaftar di *Hitech School*! 🏫\n";
        $message .= 'Jika ada pertanyaan, hubungi kami di: https://wa.me/62882019679350';

        return $this->send($whatsappNumber, $message);
    }
}
