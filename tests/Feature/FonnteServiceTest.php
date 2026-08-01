<?php

use App\Livewire\Admission\SmartForm;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Http;

it('sends a whatsapp message via fonnte api', function () {
    Http::fake([
        'api.fonnte.com/*' => Http::response([
            'status' => true,
            'detail' => 'success! message in queue',
            'id' => ['12345'],
        ], 200),
    ]);

    config(['services.fonnte.api_key' => 'test-token', 'services.fonnte.base_url' => 'https://api.fonnte.com/send']);

    $service = new FonnteService;
    $result = $service->send('08123456789', 'Test message');

    expect($result['status'])->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.fonnte.com/send'
            && $request->hasHeader('Authorization', 'test-token');
    });
});

it('sends registration billing notification', function () {
    Http::fake([
        'api.fonnte.com/*' => Http::response(['status' => true], 200),
    ]);

    config(['services.fonnte.api_key' => 'test-token', 'services.fonnte.base_url' => 'https://api.fonnte.com/send']);

    $service = new FonnteService;
    $result = $service->sendRegistrationBilling(
        whatsappNumber: '08123456789',
        studentName: 'Ahmad Fauzi',
        registrationNumber: 'SPMB-000001',
        baseFee: 300000,
        uniqueCode: 789, // last 3 digits of 08123456789
    );

    expect($result['status'])->toBeTrue();

    Http::assertSent(function ($request) {
        $message = $request->data()['message'];

        return str_contains($message, 'Ahmad Fauzi')
            && str_contains($message, 'SPMB-000001')
            && str_contains($message, '300.000')   // baseFee formatted
            && str_contains($message, '789')        // uniqueCode
            && str_contains($message, '300.789');   // total formatted
    });
});

it('adds whatsapp_number field to step 1 form validation', function () {
    $component = Livewire\Livewire::test(SmartForm::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->set('nisn', '1234567890')
        ->set('nik', '1234567890123456')
        ->set('birth_place', 'Jakarta')
        ->set('birth_date', '2005-01-01')
        ->set('gender', 'male')
        ->set('program', 'cs')
        ->set('whatsapp_number', '') // empty - should fail
        ->call('nextStep')
        ->assertHasErrors(['whatsapp_number']);
});
