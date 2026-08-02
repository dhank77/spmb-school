<?php

use App\Livewire\Admin\Pipeline;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $admin = Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'student']);

    $permission = Permission::firstOrCreate(['name' => 'access.admin_portal']);
    $admin->givePermissionTo($permission);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->student = User::factory()->student()->withStatus('menunggu_verifikasi')->create([
        'nisn' => '1234567890',
        'nik' => '1234567890123456',
        'birth_place' => 'Jakarta',
        'birth_date' => '2005-06-15',
        'gender' => 'male',
        'program' => 'IPA',
        'previous_school' => 'SMP Negeri 1',
        'graduation_year' => 2023,
    ]);
    $this->student->assignRole('student');
});

test('admin can open student detail panel by calling viewStudent', function () {
    Livewire::actingAs($this->admin)
        ->test(Pipeline::class)
        ->call('viewStudent', $this->student->id)
        ->assertSet('selectedStudentId', $this->student->id)
        ->assertSee($this->student->name)
        ->assertSee($this->student->nisn);
});

test('admin can close the student detail panel', function () {
    Livewire::actingAs($this->admin)
        ->test(Pipeline::class)
        ->call('viewStudent', $this->student->id)
        ->call('closeStudent')
        ->assertSet('selectedStudentId', null);
});

test('admin can change verification status from the detail panel', function () {
    Livewire::actingAs($this->admin)
        ->test(Pipeline::class)
        ->call('viewStudent', $this->student->id)
        ->set('verificationNotes', 'Dokumen lengkap')
        ->call('changeVerificationStatus', 'terverifikasi')
        ->assertSet('selectedStudentId', null);

    expect($this->student->fresh()->verification_status)->toBe('terverifikasi')
        ->and($this->student->fresh()->verification_notes)->toBe('Dokumen lengkap');
});

test('admin can reject student from the detail panel', function () {
    Livewire::actingAs($this->admin)
        ->test(Pipeline::class)
        ->call('viewStudent', $this->student->id)
        ->set('verificationNotes', 'Dokumen tidak valid')
        ->call('changeVerificationStatus', 'ditolak')
        ->assertSet('selectedStudentId', null);

    expect($this->student->fresh()->verification_status)->toBe('ditolak');
});

test('pipeline view shows uploaded document links in detail panel', function () {
    $this->student->update([
        'document_identity' => 'documents/identity/ktp.jpg',
        'document_diploma' => 'documents/diploma/ijazah.pdf',
    ]);

    Livewire::actingAs($this->admin)
        ->test(Pipeline::class)
        ->call('viewStudent', $this->student->id)
        ->assertSee('documents/identity/ktp.jpg')
        ->assertSee('documents/diploma/ijazah.pdf');
});
