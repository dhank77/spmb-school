<?php

namespace App\Livewire\Admin;

use App\Models\CbtExam;
use App\Models\CbtSubject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.admin-portal')]
class CbtManagement extends Component
{
    // -------------------------------------------------------------------------
    // Exam Scheduler
    // -------------------------------------------------------------------------

    #[Validate('required|exists:cbt_subjects,id')]
    public ?int $examSubjectId = null;

    #[Validate('required|string|max:255')]
    public string $examName = '';

    #[Validate('required|date|after_or_equal:today')]
    public string $examDate = '';

    #[Validate('required|in:Jam 08:00,Jam 11:00,Jam 14:00')]
    public string $examSession = 'Jam 08:00';

    #[Validate('required|in:Lab A-01,Lab A-02,Hall C,Library W')]
    public string $examRoom = '';

    public bool $scheduledSuccess = false;

    /**
     * Available session options.
     *
     * @var array<int, string>
     */
    public array $sessions = [
        'Jam 08:00',
        'Jam 11:00',
        'Jam 14:00',
    ];

    /**
     * Available room options.
     *
     * @var array<int, string>
     */
    public array $rooms = [
        'Lab A-01',
        'Lab A-02',
        'Hall C',
        'Library W',
    ];

    // -------------------------------------------------------------------------
    // Subject Modal
    // -------------------------------------------------------------------------

    public bool $showSubjectModal = false;

    public ?int $editingSubjectId = null;

    #[Validate('required|string|max:10')]
    public string $subjectCode = '';

    #[Validate('required|string|max:255')]
    public string $subjectName = '';

    #[Validate('required|string|max:255')]
    public string $subjectTopic = '';

    #[Validate('required|in:Easy,Medium,Hard')]
    public string $subjectDifficulty = 'Medium';

    /**
     * Available difficulty levels.
     *
     * @var array<int, string>
     */
    public array $difficulties = ['Easy', 'Medium', 'Hard'];

    // -------------------------------------------------------------------------
    // Exam scheduler actions
    // -------------------------------------------------------------------------

    /**
     * Schedule a new exam.
     */
    public function scheduleExam(): void
    {
        $validated = $this->validate([
            'examSubjectId' => 'required|exists:cbt_subjects,id',
            'examName' => 'required|string|max:255',
            'examDate' => 'required|date|after_or_equal:today',
            'examSession' => 'required|in:Jam 08:00,Jam 11:00,Jam 14:00',
            'examRoom' => 'required|in:Lab A-01,Lab A-02,Hall C,Library W',
        ]);

        CbtExam::create([
            'cbt_subject_id' => $validated['examSubjectId'],
            'name' => $validated['examName'],
            'date' => $validated['examDate'],
            'session' => $validated['examSession'],
            'room' => $validated['examRoom'],
        ]);

        $this->reset(['examSubjectId', 'examName', 'examDate', 'examRoom']);
        $this->examSession = 'Jam 08:00';
        $this->scheduledSuccess = true;

        $this->dispatch('exam-scheduled');
    }

    /**
     * Dismiss the success notification.
     */
    public function dismissSuccess(): void
    {
        $this->scheduledSuccess = false;
    }

    // -------------------------------------------------------------------------
    // Subject CRUD actions
    // -------------------------------------------------------------------------

    /**
     * Open the modal to create a new subject.
     */
    public function openSubjectModal(): void
    {
        $this->resetSubjectForm();
        $this->showSubjectModal = true;
    }

    /**
     * Open the modal pre-populated to edit an existing subject.
     */
    public function editSubject(int $id): void
    {
        $subject = CbtSubject::findOrFail($id);

        $this->editingSubjectId = $subject->id;
        $this->subjectCode = $subject->code;
        $this->subjectName = $subject->name;
        $this->subjectTopic = $subject->topic;
        $this->subjectDifficulty = $subject->difficulty;
        $this->showSubjectModal = true;
    }

    /**
     * Persist the subject (create or update).
     */
    public function saveSubject(): void
    {
        $validated = $this->validate([
            'subjectCode' => 'required|string|max:10',
            'subjectName' => 'required|string|max:255',
            'subjectTopic' => 'required|string|max:255',
            'subjectDifficulty' => 'required|in:Easy,Medium,Hard',
        ]);

        $data = [
            'code' => strtoupper($validated['subjectCode']),
            'name' => $validated['subjectName'],
            'topic' => $validated['subjectTopic'],
            'difficulty' => $validated['subjectDifficulty'],
        ];

        if ($this->editingSubjectId !== null) {
            CbtSubject::findOrFail($this->editingSubjectId)->update($data);
            $this->dispatch('subject-updated');
        } else {
            CbtSubject::create($data);
            $this->dispatch('subject-created');
        }

        $this->closeSubjectModal();
    }

    /**
     * Delete a subject and all its questions.
     */
    public function deleteSubject(int $id): void
    {
        CbtSubject::findOrFail($id)->delete();
        $this->dispatch('subject-deleted');
    }

    /**
     * Close the subject modal and reset form state.
     */
    public function closeSubjectModal(): void
    {
        $this->showSubjectModal = false;
        $this->resetSubjectForm();
    }

    /**
     * Reset all subject form fields.
     */
    protected function resetSubjectForm(): void
    {
        $this->reset(['editingSubjectId', 'subjectCode', 'subjectName', 'subjectTopic']);
        $this->subjectDifficulty = 'Medium';
        $this->resetValidation(['subjectCode', 'subjectName', 'subjectTopic', 'subjectDifficulty']);
    }

    // -------------------------------------------------------------------------
    // Data fetchers
    // -------------------------------------------------------------------------

    /**
     * Get all question bank subjects.
     *
     * @return Collection<int, CbtSubject>
     */
    protected function getSubjects(): Collection
    {
        return CbtSubject::withCount('questions')->orderByDesc('items_count')->get();
    }

    /**
     * Get the total number of questions in the bank.
     */
    protected function getTotalQuestions(): int
    {
        return CbtSubject::sum('items_count');
    }

    /**
     * Get upcoming scheduled exams.
     *
     * @return Collection<int, CbtExam>
     */
    protected function getUpcomingExams(): Collection
    {
        return CbtExam::with('subject')->orderBy('date')->orderBy('session')->take(5)->get();
    }

    public function render(): View
    {
        return view('livewire.admin.cbt-management', [
            'subjects' => $this->getSubjects(),
            'totalQuestions' => $this->getTotalQuestions(),
            'upcomingExams' => $this->getUpcomingExams(),
        ]);
    }
}
