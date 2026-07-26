<?php

namespace App\Livewire\Exam;

use App\Models\CbtExam;
use App\Models\CbtExamResult;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.student-portal')]
#[Title('Dashboard Ujian - Portal Calon Murid')]
class ActiveExams extends Component
{
    /**
     * Get active scheduled exams currently ongoing / available today.
     *
     * @return Collection<int, CbtExam>
     */
    protected function getActiveExams(): Collection
    {
        $exams = CbtExam::with(['subject' => function ($query) {
            $query->withCount('questions');
        }])
            ->whereDate('date', '<=', today())
            ->whereNotNull('cbt_subject_id')
            ->orderBy('date')
            ->orderBy('session')
            ->get();

        if ($exams->isEmpty()) {
            $exams = CbtExam::with(['subject' => function ($query) {
                $query->withCount('questions');
            }])
                ->whereNotNull('cbt_subject_id')
                ->orderBy('date')
                ->take(2)
                ->get();
        }

        return $exams;
    }

    /**
     * Get upcoming exam schedules (future dates).
     *
     * @return Collection<int, CbtExam>
     */
    protected function getUpcomingExams(): Collection
    {
        $exams = CbtExam::with('subject')
            ->whereDate('date', '>', today())
            ->orderBy('date')
            ->orderBy('session')
            ->take(5)
            ->get();

        if ($exams->isEmpty()) {
            $exams = CbtExam::with('subject')
                ->orderBy('date')
                ->orderBy('session')
                ->take(5)
                ->get();
        }

        return $exams;
    }

    /**
     * Get completed exam results for the authenticated student.
     *
     * @return Collection<int, CbtExamResult>
     */
    protected function getCompletedResults(): Collection
    {
        return CbtExamResult::where('user_id', Auth::id())
            ->with('subject')
            ->orderByDesc('completed_at')
            ->get();
    }

    public function render(): View
    {
        $completedResults = $this->getCompletedResults();
        $completedSubjectIds = $completedResults->pluck('cbt_subject_id')->toArray();

        return view('livewire.exam.active-exams', [
            'activeExams' => $this->getActiveExams(),
            'upcomingExams' => $this->getUpcomingExams(),
            'completedResults' => $completedResults,
            'completedSubjectIds' => $completedSubjectIds,
            'user' => Auth::user(),
        ]);
    }
}
