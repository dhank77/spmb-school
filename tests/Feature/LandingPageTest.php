<?php

use App\Models\AdmissionWave;
use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('landing page returns successful response', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
});

test('landing page displays admission waves from database', function () {
    AdmissionWave::factory()->create([
        'name' => 'Gelombang Test',
        'status' => 'active',
        'registration_cost' => 350000,
        'remaining_quota' => 42,
        'total_quota' => 120,
        'sort_order' => 1,
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Gelombang Test');
    $response->assertSee('Rp 350.000');
    $response->assertSee('42 Kursi');
});

test('landing page displays faqs from database', function () {
    Faq::factory()->create([
        'question' => 'Apa saja persyaratan utama untuk pendaftaran?',
        'answer' => 'Pendaftar perlu memberikan salinan digital ijazah sekolah sebelumnya.',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Apa saja persyaratan utama untuk pendaftaran?');
    $response->assertSee('Pendaftar perlu memberikan salinan digital ijazah sekolah sebelumnya.');
});

test('landing page does not display inactive faqs', function () {
    Faq::factory()->create([
        'question' => 'Hidden FAQ question',
        'answer' => 'This should not appear.',
        'is_active' => false,
        'sort_order' => 1,
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertDontSee('Hidden FAQ question');
});

test('wave card shows correct status for closed wave', function () {
    AdmissionWave::factory()->closed()->create([
        'name' => 'Gelombang Closed',
        'sort_order' => 1,
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Gelombang Closed');
    $response->assertSee('Kapasitas Penuh');
    $response->assertSee('Ditutup');
});

test('wave card shows correct status for active wave', function () {
    AdmissionWave::factory()->active()->create([
        'name' => 'Gelombang Active',
        'sort_order' => 1,
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Gelombang Active');
    $response->assertSee('Daftar Sekarang');
    $response->assertSee('Aktif Sekarang');
});

test('wave card shows correct status for upcoming wave', function () {
    AdmissionWave::factory()->upcoming()->create([
        'name' => 'Gelombang Upcoming',
        'sort_order' => 1,
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Gelombang Upcoming');
    $response->assertSee('Beritahu Saya');
    $response->assertSee('Mendatang');
});

test('landing page shows empty state when no waves exist', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Tidak ada gelombang penerimaan tersedia saat ini.');
});

test('landing page shows empty state when no faqs exist', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Tidak ada FAQ tersedia saat ini.');
});
