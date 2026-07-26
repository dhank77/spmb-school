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
    #[Validate('required|string|max:255')]
    public string $examName = '';

    #[Validate('required|date|after_or_equal:today')]
    public string $examDate = '';

    #[Validate('required|in:Morning (08:00),Noon (11:00),Afternoon (14:00)')]
    public string $examSession = 'Morning (08:00)';

    #[Validate('required|in:Lab A-01,Lab A-02,Hall C,Library W')]
    public string $examRoom = '';

    public bool $scheduledSuccess = false;

    /**
     * Available session options.
     *
     * @var array<int, string>
     */
    public array $sessions = [
        'Morning (08:00)',
        'Noon (11:00)',
        'Afternoon (14:00)',
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

    /**
     * Schedule a new exam.
     */
    public function scheduleExam(): void
    {
        $validated = $this->validate();

        CbtExam::create([
            'name' => $validated['examName'],
            'date' => $validated['examDate'],
            'session' => $validated['examSession'],
            'room' => $validated['examRoom'],
        ]);

        $this->reset(['examName', 'examDate', 'examSession', 'examRoom']);
        $this->examSession = 'Morning (08:00)';
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

    /**
     * Get all question bank subjects.
     *
     * @return Collection<int, CbtSubject>
     */
    protected function getSubjects(): Collection
    {
        return CbtSubject::orderByDesc('items_count')->get();
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
        return CbtExam::orderBy('date')->orderBy('session')->take(5)->get();
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
