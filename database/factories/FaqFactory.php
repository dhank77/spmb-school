<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    protected $model = Faq::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $questions = [
            'Apa saja persyaratan utama untuk pendaftaran?',
            'Apakah ada ujian masuk untuk semua gelombang?',
            'Apakah ada beasiswa untuk siswa baru?',
            'Bisakah saya mengubah program yang dipilih setelah pendaftaran?',
            'Bagaimana cara mengakses portal siswa setelah pendaftaran?',
            'Kapan pengumuman hasil seleksi biasanya diumumkan?',
        ];

        $answers = [
            'Pendaftar perlu memberikan salinan digital ijazah sekolah sebelumnya, kartu identitas (KTP/Paspor), kartu keluarga, dan foto profesional terbaru. Siswa internasional mungkin memerlukan dokumen tambahan.',
            'Ya, semua gelombang mencakup Ujian Berbasis Komputer (CBT) yang meliputi Logika, Kemampuan Berbahasa Inggris, dan Matematika Dasar. Hasil biasanya diumumkan dalam waktu 48 jam.',
            'Tentu saja. Kami menawarkan beasiswa Berdasarkan Prestasi, Berdasarkan Kebutuhan, dan Prestasi Olahraga/Seni. Pendaftaran beasiswa diproses bersamaan dengan gelombang penerimaan.',
            'Perubahan program diperbolehkan sebelum tanggal CBT. Silakan hubungi kantor penerimaan untuk bantuan transfer program internal.',
            'Portal siswa dapat diakses melalui halaman login menggunakan email dan kata sandi yang didaftarkan saat pendaftaran.',
            'Hasil seleksi biasanya diumumkan maksimal 7 hari kerja setelah pelaksanaan ujian masuk.',
        ];

        return [
            'question' => fake()->randomElement($questions),
            'answer' => fake()->randomElement($answers),
            'sort_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }

    /**
     * FAQ that is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
