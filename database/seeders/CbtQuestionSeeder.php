<?php

namespace Database\Seeders;

use App\Models\CbtQuestion;
use App\Models\CbtSubject;
use Illuminate\Database\Seeder;

class CbtQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = CbtSubject::all();

        if ($subjects->isEmpty()) {
            $this->call(CbtSubjectSeeder::class);
            $subjects = CbtSubject::all();
        }

        $sampleQuestionsMap = [
            'MA' => [
                [
                    'question_text' => 'Sebuah persegi panjang memiliki panjang 5 cm lebih dari dua kali lebarnya. Jika keliling persegi panjang adalah 58 cm, berapakah luas persegi panjang tersebut dalam centimeter persegi?',
                    'option_a' => '154 cm²',
                    'option_b' => '168 cm²',
                    'option_c' => '182 cm²',
                    'option_d' => '196 cm²',
                    'correct_answer' => 'B',
                    'points' => 2,
                ],
                [
                    'question_text' => 'Jika 3x + 7 = 22, berapakah nilai dari 2x - 3?',
                    'option_a' => '7',
                    'option_b' => '9',
                    'option_c' => '10',
                    'option_d' => '12',
                    'correct_answer' => 'A',
                    'points' => 1,
                ],
                [
                    'question_text' => 'Suatu barisan aritmatika memiliki suku ke-3 = 11 dan suku ke-7 = 27. Berapakah suku ke-10 barisan tersebut?',
                    'option_a' => '37',
                    'option_b' => '39',
                    'option_c' => '41',
                    'option_d' => '43',
                    'correct_answer' => 'B',
                    'points' => 2,
                ],
                [
                    'question_text' => 'Jika sin(A) = 3/5 dan A berada di kuadran I, berapakah nilai cos(A)?',
                    'option_a' => '3/4',
                    'option_b' => '4/5',
                    'option_c' => '5/4',
                    'option_d' => '4/3',
                    'correct_answer' => 'B',
                    'points' => 2,
                ],
                [
                    'question_text' => 'Berapakah turunan pertama dari f(x) = 3x² - 5x + 8 pada x = 2?',
                    'option_a' => '7',
                    'option_b' => '8',
                    'option_c' => '9',
                    'option_d' => '12',
                    'correct_answer' => 'A',
                    'points' => 2,
                ],
            ],
            'SC' => [
                [
                    'question_text' => 'Manakah di antara organel sel berikut yang berfungsi sebagai tempat terjadinya respirasi seluler dan penghasil ATP?',
                    'option_a' => 'Ribosom',
                    'option_b' => 'Mitokondria',
                    'option_c' => 'Lisosom',
                    'option_d' => 'Badan Golgi',
                    'correct_answer' => 'B',
                    'points' => 1,
                ],
                [
                    'question_text' => 'Proses pembelahan sel yang menghasilkan 4 sel anakan dengan jumlah kromosom separuh dari sel induk disebut?',
                    'option_a' => 'Mitosis',
                    'option_b' => 'Meiosis',
                    'option_c' => 'Amitosis',
                    'option_d' => 'Sitokinesis',
                    'correct_answer' => 'B',
                    'points' => 2,
                ],
            ],
            'EN' => [
                [
                    'question_text' => 'Choose the correct form to complete the sentence: "If she ___ earlier, she would have caught the morning train."',
                    'option_a' => 'woke up',
                    'option_b' => 'had woken up',
                    'option_c' => 'has woken up',
                    'option_d' => 'wakes up',
                    'correct_answer' => 'B',
                    'points' => 2,
                ],
                [
                    'question_text' => 'What is the synonym of the word "meticulous"?',
                    'option_a' => 'Careless',
                    'option_b' => 'Thorough',
                    'option_c' => 'Hasty',
                    'option_d' => 'Vague',
                    'correct_answer' => 'B',
                    'points' => 1,
                ],
            ],
        ];

        foreach ($subjects as $subject) {
            $questionsData = $sampleQuestionsMap[$subject->code] ?? [];

            foreach ($questionsData as $q) {
                CbtQuestion::firstOrCreate([
                    'cbt_subject_id' => $subject->id,
                    'question_text' => $q['question_text'],
                ], array_merge($q, ['cbt_subject_id' => $subject->id]));
            }

            // Ensure every subject has at least 5 questions using Factory if needed
            $existingCount = $subject->questions()->count();
            if ($existingCount < 5) {
                CbtQuestion::factory()->count(5 - $existingCount)->create([
                    'cbt_subject_id' => $subject->id,
                ]);
            }

            // Sync items_count on subject
            $subject->update(['items_count' => $subject->questions()->count()]);
        }
    }
}
