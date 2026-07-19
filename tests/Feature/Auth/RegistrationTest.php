<?php

use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'nisn' => '1234567890',
        'nik' => '1234567890123456',
        'birth_place' => 'Jakarta',
        'birth_date' => '2008-01-01',
        'gender' => 'male',
        'program' => 'IPA',
        'previous_school' => 'SMP 1 Jakarta',
        'graduation_year' => 2024,
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});
