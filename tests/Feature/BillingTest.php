<?php

use App\Livewire\Admission\Billing;
use App\Models\User;
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

test('student payment marks user as paid and redirects to dashboard', function () {
    $user = User::factory()->student()->create(['payment_status' => 'unpaid']);
    $user->assignRole('student');

    Livewire::actingAs($user)
        ->test(Billing::class)
        ->set('selectedMethod', 'BNI')
        ->call('payNow')
        ->assertRedirect(route('dashboard'));

    expect($user->fresh()->payment_status)->toBe('paid');
    expect($user->fresh()->payment_method)->toBe('BNI');
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

    // Admin should not be gated by payment middleware
    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk();
});
