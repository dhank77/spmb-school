<?php

use App\Livewire\Admission\Billing;
use App\Models\PaymentOrder;
use App\Models\User;
use App\Services\DuitkuService;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'student']);
});

test('guests are redirected to login when accessing billing page', function () {
    $this->get(route('billing'))->assertRedirect(route('login'));
});

test('authenticated unpaid student can access billing page', function () {
    $user = User::factory()->student()->create();
    $user->assignRole('student');

    $this->actingAs($user)
        ->get(route('billing'))
        ->assertOk();
});

test('billing page renders invoice information', function () {
    $user = User::factory()->student()->create();
    $user->assignRole('student');

    Livewire::actingAs($user)
        ->test(Billing::class)
        ->assertSee('Tagihan Pendaftaran')
        ->assertSee('250.000')
        ->assertSee('250.772');
});

test('student can select a payment method', function () {
    $user = User::factory()->student()->create();
    $user->assignRole('student');

    Livewire::actingAs($user)
        ->test(Billing::class)
        ->call('selectMethod', 'BCA')
        ->assertSet('selectedMethod', 'BCA');
});

test('student cannot pay without selecting a method', function () {
    $user = User::factory()->student()->create();
    $user->assignRole('student');

    Livewire::actingAs($user)
        ->test(Billing::class)
        ->call('payNow')
        ->assertHasErrors(['selectedMethod']);
});

test('clicking pay now redirects to duitku payment url', function () {
    $user = User::factory()->student()->create(['payment_status' => 'unpaid']);
    $user->assignRole('student');

    // Create a mock order that we expect DuitkuService to return
    $fakeOrder = new PaymentOrder([
        'user_id' => $user->id,
        'merchant_order_id' => 'SPMB-TEST-'.time(),
        'payment_method' => 'BC',
        'amount' => 250772,
        'status' => 'pending',
        'reference' => 'https://sandbox.duitku.com/payment/test-url',
    ]);

    $mockService = Mockery::mock(DuitkuService::class);
    $mockService->shouldReceive('createInvoice')
        ->once()
        ->andReturn($fakeOrder);

    app()->instance(DuitkuService::class, $mockService);

    Livewire::actingAs($user)
        ->test(Billing::class)
        ->set('selectedMethod', 'BCA')
        ->call('payNow')
        ->assertRedirect('https://sandbox.duitku.com/payment/test-url');
});

test('shows error message when duitku api fails', function () {
    $user = User::factory()->student()->create(['payment_status' => 'unpaid']);
    $user->assignRole('student');

    $mockService = Mockery::mock(DuitkuService::class);
    $mockService->shouldReceive('createInvoice')
        ->once()
        ->andThrow(new Exception('API error'));

    app()->instance(DuitkuService::class, $mockService);

    Livewire::actingAs($user)
        ->test(Billing::class)
        ->set('selectedMethod', 'BNI')
        ->call('payNow')
        ->assertSet('processingError', true);
});

test('unpaid student is redirected to billing when accessing dashboard', function () {
    $user = User::factory()->student()->create(['payment_status' => 'unpaid']);
    $user->assignRole('student');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('billing'));
});

test('paid student can access dashboard without redirect', function () {
    $user = User::factory()->student()->paid()->create();
    $user->assignRole('student');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

test('admin users bypass payment gate', function () {
    $admin = User::factory()->create(['payment_status' => 'unpaid']);
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk();
});
