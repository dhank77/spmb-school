<?php

namespace App\Livewire\Admin;

use App\Models\CbtQuestion;
use App\Models\CbtSubject;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Prism\Prism\Facades\Prism;
use Throwable;

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
    // AI Question Generation modal
    // -------------------------------------------------------------------------

    public bool $showAiModal = false;

    public bool $isGeneratingAi = false;

    public int $aiQuestionCount = 5;

    public string $aiTopic = '';

    public int $aiPoints = 1;

    public string $aiCustomInstruction = '';

    public ?string $aiErrorMessage = null;

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

    // -------------------------------------------------------------------------
    // AI Question Generation actions
    // -------------------------------------------------------------------------

    /**
     * Open the AI question generation modal with pre-filled topic from subject.
     */
    public function openAiModal(): void
    {
        $this->aiTopic = $this->subject->topic ?? $this->subject->name;
        $this->aiQuestionCount = 5;
        $this->aiPoints = 1;
        $this->aiCustomInstruction = '';
        $this->aiErrorMessage = null;
        $this->isGeneratingAi = false;
        $this->showAiModal = true;
    }

    /**
     * Close the AI modal and reset its state.
     */
    public function closeAiModal(): void
    {
        $this->showAiModal = false;
        $this->aiErrorMessage = null;
        $this->isGeneratingAi = false;
    }

    /**
     * Generate questions using SumoPod AI via Prism PHP structured output.
     */
    public function generateAiQuestions(): void
    {
        $this->validate([
            'aiQuestionCount' => 'required|integer|min:1|max:20',
            'aiTopic' => 'required|string|max:500',
            'aiPoints' => 'required|integer|min:1|max:100',
            'aiCustomInstruction' => 'nullable|string|max:1000',
        ]);

        $this->aiErrorMessage = null;
        $this->isGeneratingAi = true;

        try {
            // Remove PHP execution time limit for this long-running AI request.
            set_time_limit(0);

            $subjectName = $this->subject->name;
            $difficulty = $this->subject->difficulty ?? 'sedang';
            $topic = $this->aiTopic;
            $count = $this->aiQuestionCount;

            $systemPrompt = <<<'SYSTEM'
            Kamu adalah generator soal ujian profesional berbahasa Indonesia. Tugas kamu adalah membuat soal pilihan ganda (multiple choice) berkualitas tinggi.
            PENTING: Seluruh soal, pilihan jawaban, dan semua teks WAJIB ditulis dalam Bahasa Indonesia yang baik dan benar.
            Kamu WAJIB merespons HANYA dengan JSON array yang valid, tanpa teks tambahan, tanpa markdown, tanpa komentar.
            Format JSON:
            [{"question_text":"...","option_a":"...","option_b":"...","option_c":"...","option_d":"...","correct_answer":"A"}]
            correct_answer hanya boleh berisi: A, B, C, atau D.
            SYSTEM;

            $userPrompt = "Buatkan tepat {$count} soal pilihan ganda dalam Bahasa Indonesia untuk mata pelajaran \"{$subjectName}\" dengan topik \"{$topic}\".";
            $userPrompt .= " Tingkat kesulitan: {$difficulty}.";
            $userPrompt .= ' Setiap soal harus memiliki 4 pilihan jawaban yang berbeda dan hanya satu jawaban yang benar.';
            $userPrompt .= ' Buat pilihan jawaban yang bervariasi dan tidak terlalu mudah ditebak.';
            $userPrompt .= ' Semua teks HARUS dalam Bahasa Indonesia.';

            if ($this->aiCustomInstruction !== '') {
                $userPrompt .= " Instruksi tambahan: {$this->aiCustomInstruction}.";
            }

            $userPrompt .= " Balas HANYA dengan JSON array berisi tepat {$count} soal. Jangan tambahkan teks lain.";

            $response = Prism::text()
                ->using('sumopod', env('SUMOPOD_MODEL', 'MiniMax-M2.7-highspeed'))
                ->withSystemPrompt($systemPrompt)
                ->withPrompt($userPrompt)
                ->withClientOptions(['timeout' => 120, 'connect_timeout' => 15])
                ->generate();

            // Strip markdown code fences if the model wrapped the JSON
            $rawText = trim($response->text);
            $rawText = (string) preg_replace('/^```(?:json)?\s*/i', '', $rawText);
            $rawText = (string) preg_replace('/\s*```$/', '', $rawText);

            $items = json_decode($rawText, true);

            if (! is_array($items) || $items === []) {
                $this->aiErrorMessage = 'AI tidak mengembalikan soal yang valid. Coba lagi atau kurangi jumlah soal.';

                return;
            }

            foreach ($items as $item) {
                $correctAnswer = strtoupper(trim($item['correct_answer'] ?? 'A'));

                if (! in_array($correctAnswer, ['A', 'B', 'C', 'D'])) {
                    $correctAnswer = 'A';
                }

                CbtQuestion::create([
                    'cbt_subject_id' => $this->subject->id,
                    'question_text' => $item['question_text'] ?? '',
                    'option_a' => $item['option_a'] ?? '',
                    'option_b' => $item['option_b'] ?? '',
                    'option_c' => $item['option_c'] ?? '',
                    'option_d' => $item['option_d'] ?? '',
                    'correct_answer' => $correctAnswer,
                    'points' => $this->aiPoints,
                ]);
            }

            $this->syncItemsCount();
            $this->closeAiModal();
            $this->dispatch('questions-ai-generated', count: count($items));
        } catch (Throwable $e) {
            $this->aiErrorMessage = 'Terjadi kesalahan saat membuat soal: '.$e->getMessage();
        } finally {
            $this->isGeneratingAi = false;
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

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
