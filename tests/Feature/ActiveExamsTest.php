<?php

use App\Livewire\Exam\ActiveExams;
use App\Models\CbtExam;
use App\Models\CbtSubject;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
});

test('active exams page requires authentication', function () {
    $this->get('/exam/active')
        ->assertRedirect('/login');
});

test('authenticated student can view active exams dashboard page', function () {
    $student = User::factory()->paid()->create();
    $student->assignRole('student');

    $subject = CbtSubject::factory()->create(['name' => 'Fisika Dasar', 'code' => 'FD']);
    $exam = CbtExam::factory()->create(['cbt_subject_id' => $subject->id, 'name' => 'Ujian TPA Mandiri']);

    $this->actingAs($student)
        ->get('/exam/active')
        ->assertOk()
        ->assertSee('Fisika Dasar')
        ->assertSee('Ujian TPA Mandiri')
        ->assertSeeLivewire(ActiveExams::class);
});

test('active exams dashboard displays subjects and schedules from database', function () {
    $student = User::factory()->paid()->create();
    $student->assignRole('student');

    $subject = CbtSubject::factory()->create(['name' => 'Kimia Organik']);
    CbtExam::factory()->create(['cbt_subject_id' => $subject->id, 'name' => 'Jadwal Simulasi CBT']);

    Livewire::actingAs($student)
        ->test(ActiveExams::class)
        ->assertSee('Kimia Organik')
        ->assertSee('Jadwal Simulasi CBT');
});
