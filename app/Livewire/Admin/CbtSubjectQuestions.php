<?php

namespace App\Livewire\Admin;

use App\Models\CbtQuestion;
use App\Models\CbtSubject;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin-portal')]
class CbtSubjectQuestions extends Component
{
    use WithPagination;

    public CbtSubject $subject;

    // -------------------------------------------------------------------------
    // Question modal
    // -------------------------------------------------------------------------

    public bool $showQuestionModal = false;

    public ?int $editingQuestionId = null;

    public string $questionText = '';

    public string $optionA = '';

    public string $optionB = '';

    public string $optionC = '';

    public string $optionD = '';

    public string $correctAnswer = 'A';

    public int $points = 1;

    /**
     * Available answer options.
     *
     * @var array<string, string>
     */
    public array $answerOptions = [
        'A' => 'Opsi A',
        'B' => 'Opsi B',
        'C' => 'Opsi C',
        'D' => 'Opsi D',
    ];

    // -------------------------------------------------------------------------
    // Question CRUD actions
    // -------------------------------------------------------------------------

    /**
     * Open the modal to create a new question.
     */
    public function openQuestionModal(): void
    {
        $this->resetQuestionForm();
        $this->showQuestionModal = true;
    }

    /**
     * Open the modal pre-populated to edit an existing question.
     */
    public function editQuestion(int $id): void
    {
        $question = CbtQuestion::findOrFail($id);

        $this->editingQuestionId = $question->id;
        $this->questionText = $question->question_text;
        $this->optionA = $question->option_a;
        $this->optionB = $question->option_b;
        $this->optionC = $question->option_c;
        $this->optionD = $question->option_d;
        $this->correctAnswer = $question->correct_answer;
        $this->points = $question->points;
        $this->showQuestionModal = true;
    }

    /**
     * Persist the question (create or update) and sync items_count on the subject.
     */
    public function saveQuestion(): void
    {
        $this->validate([
            'questionText' => 'required|string',
            'optionA' => 'required|string|max:500',
            'optionB' => 'required|string|max:500',
            'optionC' => 'required|string|max:500',
            'optionD' => 'required|string|max:500',
            'correctAnswer' => 'required|in:A,B,C,D',
            'points' => 'required|integer|min:1|max:100',
        ]);

        $data = [
            'cbt_subject_id' => $this->subject->id,
            'question_text' => $this->questionText,
            'option_a' => $this->optionA,
            'option_b' => $this->optionB,
            'option_c' => $this->optionC,
            'option_d' => $this->optionD,
            'correct_answer' => $this->correctAnswer,
            'points' => $this->points,
        ];

        if ($this->editingQuestionId !== null) {
            CbtQuestion::findOrFail($this->editingQuestionId)->update($data);
            $event = 'question-updated';
        } else {
            CbtQuestion::create($data);
            $event = 'question-created';
        }

        $this->syncItemsCount();
        $this->closeQuestionModal();
        $this->dispatch($event);
    }

    /**
     * Delete a question and sync items_count on the subject.
     */
    public function deleteQuestion(int $id): void
    {
        CbtQuestion::findOrFail($id)->delete();
        $this->syncItemsCount();
        $this->dispatch('question-deleted');
    }

    /**
     * Close the question modal and reset form state.
     */
    public function closeQuestionModal(): void
    {
        $this->showQuestionModal = false;
        $this->resetQuestionForm();
    }

    /**
     * Reset all question form fields.
     */
    protected function resetQuestionForm(): void
    {
        $this->reset(['editingQuestionId', 'questionText', 'optionA', 'optionB', 'optionC', 'optionD']);
        $this->correctAnswer = 'A';
        $this->points = 1;
        $this->resetValidation();
    }

    /**
     * Keep items_count on the subject in sync with the actual question count.
     */
    protected function syncItemsCount(): void
    {
        $this->subject->update([
            'items_count' => $this->subject->questions()->count(),
        ]);
        $this->subject->refresh();
    }

    public function render(): View
    {
        $questions = $this->subject->questions()->latest()->paginate(15);

        return view('livewire.admin.cbt-subject-questions', [
            'questions' => $questions,
        ]);
    }
}
