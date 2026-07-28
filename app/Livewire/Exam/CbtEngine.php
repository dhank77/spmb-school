<?php

namespace App\Livewire\Exam;

use App\Models\CbtExamResult;
use App\Models\CbtQuestion;
use App\Models\CbtSubject;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.exam-fullscreen')]
#[Title('CBT Exam Engine - Strict Mode')]
class CbtEngine extends Component
{
    public ?CbtSubject $subject = null;

    /**
     * Questions array directly loaded from CbtQuestion database records.
     *
     * @var array<int, array{id: int, question_text: string, option_a: string, option_b: string, option_c: string, option_d: string, correct_answer: string, points: int}>
     */
    public array $questionsList = [];

    public int $currentIndex = 0;

    /**
     * Map of index => selected option ('A', 'B', 'C', 'D').
     *
     * @var array<int, string>
     */
    public array $answers = [];

    /**
     * Map of index => boolean (is flagged).
     *
     * @var array<int, bool>
     */
    public array $flagged = [];

    public int $remainingSeconds = 3600; // 60 minutes default

    public bool $isSubmitted = false;

    public int $finalScore = 0;

    public int $totalPoints = 0;

    public int $correctCount = 0;

    public function mount(?CbtSubject $subject = null): void
    {
        if ($subject && $subject->exists) {
            $this->subject = $subject;
        } else {
            // Find first subject with questions, or fallback to first subject
            $this->subject = CbtSubject::has('questions')->first() ?? CbtSubject::first();
        }

        if ($this->subject) {
            $dbQuestions = CbtQuestion::where('cbt_subject_id', $this->subject->id)->get();

            if ($dbQuestions->count() > 0) {
                $this->questionsList = $dbQuestions->map(fn (CbtQuestion $q) => [
                    'id' => $q->id,
                    'question_text' => $q->question_text,
                    'option_a' => $q->option_a,
                    'option_b' => $q->option_b,
                    'option_c' => $q->option_c,
                    'option_d' => $q->option_d,
                    'correct_answer' => $q->correct_answer,
                    'points' => $q->points,
                ])->toArray();
            }

            // Check if student has ALREADY submitted/completed this exam
            $existingResult = CbtExamResult::where('user_id', Auth::id())
                ->where('cbt_subject_id', $this->subject->id)
                ->first();

            if ($existingResult) {
                $this->isSubmitted = true;
                $this->finalScore = $existingResult->score;
                $this->totalPoints = $existingResult->total_points;
                $this->correctCount = $existingResult->correct_count;
            }
        }

        // Initialize empty flagged array
        foreach ($this->questionsList as $idx => $q) {
            $this->flagged[$idx] = false;
        }
    }

    public function selectOption(string $option): void
    {
        if ($this->isSubmitted) {
            return;
        }

        if (in_array($option, ['A', 'B', 'C', 'D'])) {
            $this->answers[$this->currentIndex] = $option;
        }
    }

    public function clearAnswer(): void
    {
        if ($this->isSubmitted) {
            return;
        }

        unset($this->answers[$this->currentIndex]);
    }

    public function toggleFlag(): void
    {
        if ($this->isSubmitted) {
            return;
        }

        $this->flagged[$this->currentIndex] = ! ($this->flagged[$this->currentIndex] ?? false);
    }

    public function goToQuestion(int $index): void
    {
        if ($index >= 0 && $index < count($this->questionsList)) {
            $this->currentIndex = $index;
        }
    }

    public function nextQuestion(): void
    {
        if ($this->currentIndex < count($this->questionsList) - 1) {
            $this->currentIndex++;
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function finishExam(): void
    {
        if ($this->isSubmitted) {
            return;
        }

        $this->isSubmitted = true;
        $this->correctCount = 0;
        $this->finalScore = 0;
        $this->totalPoints = 0;

        foreach ($this->questionsList as $idx => $q) {
            $this->totalPoints += $q['points'];
            if (isset($this->answers[$idx]) && $this->answers[$idx] === $q['correct_answer']) {
                $this->correctCount++;
                $this->finalScore += $q['points'];
            }
        }

        // Save result to Database so student cannot retake the exam
        if ($this->subject && Auth::check()) {
            CbtExamResult::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'cbt_subject_id' => $this->subject->id,
                ],
                [
                    'score' => $this->finalScore,
                    'total_points' => $this->totalPoints,
                    'correct_count' => $this->correctCount,
                    'total_questions' => count($this->questionsList),
                    'status' => 'completed',
                    'completed_at' => now(),
                ]
            );
        }
    }

    public function render(): View
    {
        return view('livewire.exam.cbt-engine', [
            'currentQuestion' => $this->questionsList[$this->currentIndex] ?? null,
            'totalQuestionsCount' => count($this->questionsList),
            'user' => Auth::user(),
        ]);
    }
}
