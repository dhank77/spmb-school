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
});

test('pipeline page requires authentication', function () {
    $this->get('/admin/pipeline')
        ->assertRedirect('/login');
});

test('pipeline page requires admin role', function () {
    $student = User::factory()->create();
    $student->assignRole('student');

    $this->actingAs($student)
        ->get('/admin/pipeline')
        ->assertForbidden();
});

test('admin can view pipeline page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get('/admin/pipeline')
        ->assertOk()
        ->assertSeeLivewire(Pipeline::class);
});

test('pipeline displays students grouped by verification status', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $tersimpan = User::factory()->student()->withStatus('tersimpan')->create();
    $tersimpan->assignRole('student');

    $menunggu = User::factory()->student()->withStatus('menunggu_verifikasi')->create();
    $menunggu->assignRole('student');

    $verified = User::factory()->student()->withStatus('terverifikasi')->create();
    $verified->assignRole('student');

    $rejected = User::factory()->student()->rejected()->create();
    $rejected->assignRole('student');

    Livewire::actingAs($admin)
        ->test(Pipeline::class)
        ->assertSee($tersimpan->name)
        ->assertSee($menunggu->name)
        ->assertSee($verified->name)
        ->assertSee($rejected->name);
});

test('pipeline search filters applicants by name', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $matchingStudent = User::factory()->student()->withStatus('tersimpan')->create(['name' => 'John Doe Unique']);
    $matchingStudent->assignRole('student');

    $otherStudent = User::factory()->student()->withStatus('tersimpan')->create(['name' => 'Completely Different']);
    $otherStudent->assignRole('student');

    Livewire::actingAs($admin)
        ->test(Pipeline::class)
        ->set('search', 'John Doe Unique')
        ->assertSee('John Doe Unique')
        ->assertDontSee('Completely Different');
});

test('admin can update student verification status', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $student = User::factory()->student()->withStatus('tersimpan')->create();
    $student->assignRole('student');

    Livewire::actingAs($admin)
        ->test(Pipeline::class)
        ->call('updateStatus', $student->id, 'terverifikasi');

    expect($student->fresh()->verification_status)->toBe('terverifikasi');
});

test('update status rejects invalid status values', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $student = User::factory()->student()->withStatus('tersimpan')->create();
    $student->assignRole('student');

    Livewire::actingAs($admin)
        ->test(Pipeline::class)
        ->call('updateStatus', $student->id, 'invalid_status');

    expect($student->fresh()->verification_status)->toBe('tersimpan');
});

test('pipeline displays total applicant count', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    User::factory()->count(5)->student()->withStatus('tersimpan')->create()
        ->each(fn (User $user) => $user->assignRole('student'));

    Livewire::actingAs($admin)
        ->test(Pipeline::class)
        ->assertViewHas('totalApplicants', 5);
});

test('user document progress is calculated correctly', function () {
    $user = User::factory()->create([
        'nisn' => '1234567890',
        'nik' => '1234567890123456',
        'document_identity' => 'id_card.pdf',
        'document_diploma' => null,
        'previous_school' => 'SMP Test',
    ]);

    expect($user->documents_submitted)->toBe(4)
        ->and($user->documents_total)->toBe(5)
        ->and($user->document_progress)->toBe(80);
});
