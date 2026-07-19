<?php

use App\Livewire\Admin\RolePermissionSettings;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
});

test('role settings page requires auth and admin role', function () {
    $this->get('/admin/roles')
        ->assertRedirect('/login');

    $student = User::factory()->create();
    $student->assignRole('student');

    $this->actingAs($student)
        ->get('/admin/roles')
        ->assertForbidden();
});

test('admin can view role settings page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get('/admin/roles')
        ->assertOk()
        ->assertSeeLivewire(RolePermissionSettings::class);
});

test('can select a role and load its permissions', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $role = Role::firstOrCreate(['name' => 'admissions_officer', 'guard_name' => 'web']);
    $permission = Permission::firstOrCreate(['name' => 'admissions.view', 'guard_name' => 'web']);
    $role->givePermissionTo($permission);

    $component = Livewire::actingAs($admin)
        ->test(RolePermissionSettings::class)
        ->call('selectRole', $role->id)
        ->assertSet('selectedRole.id', $role->id);

    expect($component->get('rolePermissions'))->toContain('admissions.view');
});

test('can toggle a permission and save it', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $role = Role::firstOrCreate(['name' => 'admissions_officer', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'admissions.view', 'guard_name' => 'web']);

    Livewire::actingAs($admin)
        ->test(RolePermissionSettings::class)
        ->call('selectRole', $role->id)
        ->call('togglePermission', 'admissions.view')
        ->call('savePermissions')
        ->assertDispatched('permissions-saved');

    expect($role->hasPermissionTo('admissions.view'))->toBeTrue();
});

test('can create a new role', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(RolePermissionSettings::class)
        ->set('newRoleName', 'Guest Editor')
        ->call('createRole')
        ->assertDispatched('role-created');

    expect(Role::where('name', 'guest_editor')->exists())->toBeTrue();
});
