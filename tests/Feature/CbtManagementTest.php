<?php

use App\Livewire\Admin\CbtManagement;
use App\Models\CbtExam;
use App\Models\CbtSubject;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

    $portalPermission = Permission::firstOrCreate(['name' => 'access.admin_portal', 'guard_name' => 'web']);
    $cbtCreate = Permission::firstOrCreate(['name' => 'cbt.create', 'guard_name' => 'web']);
    $cbtMonitor = Permission::firstOrCreate(['name' => 'cbt.monitor', 'guard_name' => 'web']);
    $cbtGrade = Permission::firstOrCreate(['name' => 'cbt.grade', 'guard_name' => 'web']);

    $admin->givePermissionTo([$portalPermission, $cbtCreate, $cbtMonitor, $cbtGrade]);
});

test('cbt management page requires authentication', function () {
    $this->get('/admin/cbt')
        ->assertRedirect('/login');
});

test('cbt management page requires admin portal access', function () {
    $student = User::factory()->paid()->create();
    $student->assignRole('student');

    $this->actingAs($student)
        ->get('/admin/cbt')
        ->assertForbidden();
});

test('admin can view cbt management page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get('/admin/cbt')
        ->assertOk()
        ->assertSeeLivewire(CbtManagement::class);
});

test('cbt management page shows question bank subjects', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $subject = CbtSubject::factory()->create([
        'name' => 'Mathematics',
        'topic' => 'Calculus',
        'items_count' => 1500,
        'difficulty' => 'Hard',
    ]);

    Livewire::actingAs($admin)
        ->test(CbtManagement::class)
        ->assertSee('Mathematics')
        ->assertSee('Calculus')
        ->assertSee('1,500');
});

test('cbt management shows total questions count', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    CbtSubject::factory()->create(['items_count' => 1000]);
    CbtSubject::factory()->create(['items_count' => 500]);

    Livewire::actingAs($admin)
        ->test(CbtManagement::class)
        ->assertViewHas('totalQuestions', 1500);
});

test('admin can schedule a new exam', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(CbtManagement::class)
        ->set('examName', 'Midterm Batch A')
        ->set('examDate', now()->addDays(5)->format('Y-m-d'))
        ->set('examSession', 'Morning (08:00)')
        ->set('examRoom', 'Lab A-01')
        ->call('scheduleExam')
        ->assertDispatched('exam-scheduled')
        ->assertSet('scheduledSuccess', true);

    expect(CbtExam::where('name', 'Midterm Batch A')->exists())->toBeTrue();
});

test('scheduling an exam validates required fields', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(CbtManagement::class)
        ->set('examName', '')
        ->set('examDate', '')
        ->set('examRoom', '')
        ->call('scheduleExam')
        ->assertHasErrors(['examName', 'examDate', 'examRoom']);

    expect(CbtExam::count())->toBe(0);
});

test('scheduling exam rejects past dates', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(CbtManagement::class)
        ->set('examName', 'Past Exam')
        ->set('examDate', now()->subDays(3)->format('Y-m-d'))
        ->set('examSession', 'Morning (08:00)')
        ->set('examRoom', 'Lab A-01')
        ->call('scheduleExam')
        ->assertHasErrors(['examDate']);
});

test('dismiss success hides the notification', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(CbtManagement::class)
        ->set('examName', 'Final Batch B')
        ->set('examDate', now()->addWeek()->format('Y-m-d'))
        ->set('examSession', 'Noon (11:00)')
        ->set('examRoom', 'Hall C')
        ->call('scheduleExam')
        ->assertSet('scheduledSuccess', true)
        ->call('dismissSuccess')
        ->assertSet('scheduledSuccess', false);
});

test('upcoming exams are shown on the scheduler panel', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $exam = CbtExam::factory()->create([
        'name' => 'Biology Final',
        'date' => now()->addDays(2)->format('Y-m-d'),
        'session' => 'Morning (08:00)',
        'room' => 'Lab A-02',
    ]);

    Livewire::actingAs($admin)
        ->test(CbtManagement::class)
        ->assertSee('Biology Final');
});
