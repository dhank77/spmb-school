<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Apa saja persyaratan utama untuk pendaftaran?',
                'answer' => 'Pendaftar perlu memberikan salinan digital ijazah sekolah sebelumnya, kartu identitas (KTP/Paspor), kartu keluarga, dan foto profesional terbaru. Siswa internasional mungkin memerlukan dokumen tambahan.',
                'sort_order' => 1,
            ],
            [
                'question' => 'Apakah ada ujian masuk untuk semua gelombang?',
                'answer' => 'Ya, semua gelombang mencakup Ujian Berbasis Komputer (CBT) yang meliputi Logika, Kemampuan Berbahasa Inggris, dan Matematika Dasar. Hasil biasanya diumumkan dalam waktu 48 jam.',
                'sort_order' => 2,
            ],
            [
                'question' => 'Apakah ada beasiswa untuk siswa baru?',
                'answer' => 'Tentu saja. Kami menawarkan beasiswa Berdasarkan Prestasi, Berdasarkan Kebutuhan, dan Prestasi Olahraga/Seni. Pendaftaran beasiswa diproses bersamaan dengan gelombang penerimaan.',
                'sort_order' => 3,
            ],
            [
                'question' => 'Bisakah saya mengubah program yang dipilih setelah pendaftaran?',
                'answer' => 'Perubahan program diperbolehkan sebelum tanggal CBT. Silakan hubungi kantor penerimaan untuk bantuan transfer program internal.',
                'sort_order' => 4,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['is_active' => true]));
        }
    }
}
