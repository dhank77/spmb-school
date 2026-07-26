<?php

use App\Livewire\Admin\CbtSubjectQuestions;
use App\Models\CbtQuestion;
use App\Models\CbtSubject;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

    $portalPermission = Permission::firstOrCreate(['name' => 'access.admin_portal', 'guard_name' => 'web']);
    $admin->givePermissionTo($portalPermission);
});

test('subject questions page requires authentication', function () {
    $subject = CbtSubject::factory()->create();

    $this->get(route('admin.cbt.subjects.questions', $subject))
        ->assertRedirect('/login');
});

test('admin can view subject questions page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $subject = CbtSubject::factory()->create(['name' => 'Logika']);

    $this->actingAs($admin)
        ->get(route('admin.cbt.subjects.questions', $subject))
        ->assertOk()
        ->assertSee('Logika')
        ->assertSeeLivewire(CbtSubjectQuestions::class);
});

test('admin can add a question to a subject', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $subject = CbtSubject::factory()->create(['items_count' => 0]);

    Livewire::actingAs($admin)
        ->test(CbtSubjectQuestions::class, ['subject' => $subject])
        ->call('openQuestionModal')
        ->assertSet('showQuestionModal', true)
        ->set('questionText', 'Berapakah 2 + 2?')
        ->set('optionA', '3')
        ->set('optionB', '4')
        ->set('optionC', '5')
        ->set('optionD', '6')
        ->set('correctAnswer', 'B')
        ->set('points', 2)
        ->call('saveQuestion')
        ->assertDispatched('question-created')
        ->assertSet('showQuestionModal', false);

    expect(CbtQuestion::where('question_text', 'Berapakah 2 + 2?')->exists())->toBeTrue()
        ->and($subject->fresh()->items_count)->toBe(1);
});

test('admin can edit a question', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $subject = CbtSubject::factory()->create();
    $question = CbtQuestion::factory()->create([
        'cbt_subject_id' => $subject->id,
        'question_text' => 'Soal Lama',
    ]);

    Livewire::actingAs($admin)
        ->test(CbtSubjectQuestions::class, ['subject' => $subject])
        ->call('editQuestion', $question->id)
        ->assertSet('questionText', 'Soal Lama')
        ->set('questionText', 'Soal Terupdate')
        ->call('saveQuestion')
        ->assertDispatched('question-updated');

    expect($question->fresh()->question_text)->toBe('Soal Terupdate');
});

test('admin can delete a question and subject items_count decrements', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $subject = CbtSubject::factory()->create(['items_count' => 1]);
    $question = CbtQuestion::factory()->create(['cbt_subject_id' => $subject->id]);

    Livewire::actingAs($admin)
        ->test(CbtSubjectQuestions::class, ['subject' => $subject])
        ->call('deleteQuestion', $question->id)
        ->assertDispatched('question-deleted');

    expect(CbtQuestion::where('id', $question->id)->exists())->toBeFalse()
        ->and($subject->fresh()->items_count)->toBe(0);
});
