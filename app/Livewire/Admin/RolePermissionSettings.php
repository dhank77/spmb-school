<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSettings extends Component
{
    public $roles;

    public $selectedRole = null;

    public $permissionsByCategory = [];

    // Store selected permissions for the active role
    public $rolePermissions = [];

    // For creating new role
    public $newRoleName = '';

    protected $rules = [
        'newRoleName' => 'required|min:3|unique:roles,name',
    ];

    public function mount()
    {
        $this->roles = Role::withCount('users')->get();
        if ($this->roles->count() > 0) {
            $this->selectRole($this->roles->first()->id);
        }

        $this->loadPermissions();
    }

    public function refreshRoles()
    {
        $this->roles = Role::withCount('users')->get();
    }

    public function selectRole($roleId)
    {
        $this->selectedRole = Role::findById($roleId);
        $this->rolePermissions = $this->selectedRole->permissions->pluck('name')->toArray();
    }

    public function createRole()
    {
        $this->newRoleName = str()->snake($this->newRoleName);

        $this->validate();

        Role::create(['name' => $this->newRoleName]);

        $this->newRoleName = '';
        $this->refreshRoles();

        $this->dispatch('role-created');
        session()->flash('message', 'Role created successfully.');
    }

    public function togglePermission($permissionName)
    {
        if (in_array($permissionName, $this->rolePermissions)) {
            $this->rolePermissions = array_diff($this->rolePermissions, [$permissionName]);
        } else {
            $this->rolePermissions[] = $permissionName;
        }
    }

    public function loadPermissions()
    {
        $this->permissionsByCategory = [
            'Admissions Module' => [
                ['name' => 'admissions.view', 'label' => 'View Applicant Details', 'icon' => 'groups'],
                ['name' => 'admissions.verify', 'label' => 'Verify Documents', 'icon' => 'groups'],
                ['name' => 'admissions.reject', 'label' => 'Reject Application', 'icon' => 'groups'],
            ],
            'Exam & CBT Module' => [
                ['name' => 'cbt.create', 'label' => 'Create Exam Paper', 'icon' => 'assignment_turned_in'],
                ['name' => 'cbt.monitor', 'label' => 'Monitor Live Sessions', 'icon' => 'assignment_turned_in'],
                ['name' => 'cbt.grade', 'label' => 'Manual Grading', 'icon' => 'assignment_turned_in'],
            ],
            'Finance Module' => [
                ['name' => 'finance.invoice', 'label' => 'Generate Invoice', 'icon' => 'payments'],
                ['name' => 'finance.reports', 'label' => 'View Reports', 'icon' => 'payments'],
                ['name' => 'finance.discount', 'label' => 'Adjust Discount', 'icon' => 'payments'],
            ],
            'User Management' => [
                ['name' => 'users.add_admin', 'label' => 'Add Admin Staff', 'icon' => 'manage_accounts'],
                ['name' => 'users.manage_roles', 'label' => 'Set & Change Roles', 'icon' => 'manage_accounts'],
            ],
            'System Access' => [
                ['name' => 'access.admin_portal', 'label' => 'Access Admin Portal', 'icon' => 'shield'],
            ],
            'Student Portal' => [
                ['name' => 'student.active_exams', 'label' => 'Access Active Exams', 'icon' => 'assignment'],
                ['name' => 'student.exam_history', 'label' => 'View Exam History', 'icon' => 'history'],
            ],
        ];

        // Ensure permissions exist in DB so we can assign them without errors
        foreach ($this->permissionsByCategory as $category => $perms) {
            foreach ($perms as $perm) {
                Permission::firstOrCreate(['name' => $perm['name']]);
            }
        }

        // Convey convenience permissions to admin & super_admin
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->syncPermissions(Permission::all());
        }

        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions(Permission::all());
        }

        // Grant access.admin_portal permission to other administrative roles
        $rolesToAccessPortal = ['admissions_officer', 'finance_staff', 'cbt_proctor'];
        foreach ($rolesToAccessPortal as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo('access.admin_portal');
            }
        }

        // Grant student portal permissions to student role
        $studentRole = Role::where('name', 'student')->first();
        if ($studentRole) {
            $studentRole->givePermissionTo([
                'student.active_exams',
                'student.exam_history',
            ]);
        }
    }

    public function savePermissions()
    {
        if ($this->selectedRole) {
            $this->selectedRole->syncPermissions($this->rolePermissions);
            $this->dispatch('permissions-saved');
        }
    }

    public function render()
    {
        return view('livewire.admin.role-permission-settings')
            ->layout('components.layouts.admin-portal');
    }
}
