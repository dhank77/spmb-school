<?php

namespace Database\Seeders;

use App\Models\CbtSubject;
use Illuminate\Database\Seeder;

class CbtSubjectSeeder extends Seeder
{
    /**
     * @var array<int, array{code: string, name: string, topic: string, items_count: int, difficulty: string}>
     */
    private array $subjects = [
        ['code' => 'MA', 'name' => 'Mathematics', 'topic' => 'Calculus & Algebra', 'items_count' => 1240, 'difficulty' => 'Hard'],
        ['code' => 'SC', 'name' => 'Biology', 'topic' => 'Genetics & Cell Biol.', 'items_count' => 850, 'difficulty' => 'Medium'],
        ['code' => 'EN', 'name' => 'English Literacy', 'topic' => 'Reading Comp.', 'items_count' => 2100, 'difficulty' => 'Easy'],
        ['code' => 'PH', 'name' => 'Physics', 'topic' => 'Mechanics & Waves', 'items_count' => 980, 'difficulty' => 'Hard'],
        ['code' => 'CH', 'name' => 'Chemistry', 'topic' => 'Organic & Inorganic', 'items_count' => 1450, 'difficulty' => 'Hard'],
        ['code' => 'LG', 'name' => 'Logic & Reasoning', 'topic' => 'Pattern Recognition', 'items_count' => 760, 'difficulty' => 'Medium'],
        ['code' => 'GE', 'name' => 'General Knowledge', 'topic' => 'National & Global', 'items_count' => 2380, 'difficulty' => 'Easy'],
        ['code' => 'ID', 'name' => 'Bahasa Indonesia', 'topic' => 'Analisis Teks & PUEBI', 'items_count' => 1720, 'difficulty' => 'Medium'],
    ];

    public function run(): void
    {
        foreach ($this->subjects as $subject) {
            CbtSubject::firstOrCreate(['code' => $subject['code']], $subject);
        }
    }
}
