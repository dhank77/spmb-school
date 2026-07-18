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
    $response->assertSee('42 Seats');
});

test('landing page displays faqs from database', function () {
    Faq::factory()->create([
        'question' => 'What is the test about?',
        'answer' => 'It covers logic and math.',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('What is the test about?');
    $response->assertSee('It covers logic and math.');
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
    $response->assertSee('Full Capacity');
    $response->assertSee('Closed');
});

test('wave card shows correct status for active wave', function () {
    AdmissionWave::factory()->active()->create([
        'name' => 'Gelombang Active',
        'sort_order' => 1,
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Gelombang Active');
    $response->assertSee('Apply Now');
    $response->assertSee('Active Now');
});

test('wave card shows correct status for upcoming wave', function () {
    AdmissionWave::factory()->upcoming()->create([
        'name' => 'Gelombang Upcoming',
        'sort_order' => 1,
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Gelombang Upcoming');
    $response->assertSee('Notify Me');
    $response->assertSee('Upcoming');
});

test('landing page shows empty state when no waves exist', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('No admission waves available at the moment.');
});

test('landing page shows empty state when no faqs exist', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('No FAQs available at the moment.');
});
