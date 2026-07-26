<?php

use App\Livewire\Exam\CbtEngine;
use App\Models\CbtQuestion;
use App\Models\CbtSubject;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
});

test('cbt engine page requires authentication', function () {
    $this->get('/exam/cbt')
        ->assertRedirect('/login');
});

test('authenticated user can view cbt engine page', function () {
    $student = User::factory()->paid()->create();
    $student->assignRole('student');

    $this->actingAs($student)
        ->get('/exam/cbt')
        ->assertOk()
        ->assertSeeLivewire(CbtEngine::class);
});

test('cbt engine loads subject questions from database', function () {
    $student = User::factory()->paid()->create();
    $student->assignRole('student');

    $subject = CbtSubject::factory()->create(['name' => 'Fisika Kuantum']);
    CbtQuestion::factory()->create([
        'cbt_subject_id' => $subject->id,
        'question_text' => 'Berapakah kecepatan cahaya dalam ruang hampa?',
    ]);

    Livewire::actingAs($student)
        ->test(CbtEngine::class, ['subject' => $subject])
        ->assertSee('Fisika Kuantum')
        ->assertSee('Berapakah kecepatan cahaya dalam ruang hampa?');
});

test('user can navigate between questions', function () {
    $student = User::factory()->paid()->create();
    $student->assignRole('student');

    $subject = CbtSubject::factory()->create();
    CbtQuestion::factory()->count(5)->create(['cbt_subject_id' => $subject->id]);

    Livewire::actingAs($student)
        ->test(CbtEngine::class, ['subject' => $subject])
        ->assertSet('currentIndex', 0)
        ->call('nextQuestion')
        ->assertSet('currentIndex', 1)
        ->call('previousQuestion')
        ->assertSet('currentIndex', 0)
        ->call('goToQuestion', 3)
        ->assertSet('currentIndex', 3);
});

test('user can select and clear answer', function () {
    $student = User::factory()->paid()->create();
    $student->assignRole('student');

    $subject = CbtSubject::factory()->create();
    CbtQuestion::factory()->create(['cbt_subject_id' => $subject->id]);

    Livewire::actingAs($student)
        ->test(CbtEngine::class, ['subject' => $subject])
        ->call('selectOption', 'B')
        ->assertSet('answers.0', 'B')
        ->call('clearAnswer')
        ->assertSet('answers.0', null);
});

test('user can flag question', function () {
    $student = User::factory()->paid()->create();
    $student->assignRole('student');

    $subject = CbtSubject::factory()->create();
    CbtQuestion::factory()->create(['cbt_subject_id' => $subject->id]);

    Livewire::actingAs($student)
        ->test(CbtEngine::class, ['subject' => $subject])
        ->assertSet('flagged.0', false)
        ->call('toggleFlag')
        ->assertSet('flagged.0', true)
        ->call('toggleFlag')
        ->assertSet('flagged.0', false);
});

test('user can submit exam and calculate final score', function () {
    $student = User::factory()->paid()->create();
    $student->assignRole('student');

    $subject = CbtSubject::factory()->create();
    $q1 = CbtQuestion::factory()->create([
        'cbt_subject_id' => $subject->id,
        'correct_answer' => 'B',
        'points' => 2,
    ]);
    $q2 = CbtQuestion::factory()->create([
        'cbt_subject_id' => $subject->id,
        'correct_answer' => 'A',
        'points' => 3,
    ]);

    Livewire::actingAs($student)
        ->test(CbtEngine::class, ['subject' => $subject])
        ->call('selectOption', 'B') // Q1 correct answer is B
        ->call('nextQuestion')
        ->call('selectOption', 'A') // Q2 correct answer is A
        ->call('finishExam')
        ->assertSet('isSubmitted', true)
        ->assertSet('correctCount', 2)
        ->assertSet('finalScore', 5)
        ->assertSee('Ujian Selesai!');
});
