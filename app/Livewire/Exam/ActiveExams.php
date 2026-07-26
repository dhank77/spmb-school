<?php

namespace App\Livewire\Exam;

use App\Models\CbtExam;
use App\Models\CbtSubject;
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
     * Get active question bank subjects for exams.
     *
     * @return Collection<int, CbtSubject>
     */
    protected function getActiveSubjects(): Collection
    {
        return CbtSubject::withCount('questions')->orderByDesc('items_count')->get();
    }

    /**
     * Get upcoming exam schedules.
     *
     * @return Collection<int, CbtExam>
     */
    protected function getUpcomingExams(): Collection
    {
        return CbtExam::orderBy('date')->orderBy('session')->take(5)->get();
    }

    public function render(): View
    {
        return view('livewire.exam.active-exams', [
            'activeSubjects' => $this->getActiveSubjects(),
            'upcomingExams' => $this->getUpcomingExams(),
            'user' => Auth::user(),
        ]);
    }
}
