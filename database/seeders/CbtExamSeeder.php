<?php

namespace Database\Seeders;

use App\Models\CbtExam;
use Illuminate\Database\Seeder;

class CbtExamSeeder extends Seeder
{
    /**
     * @var array<int, array{name: string, date: string, session: string, room: string}>
     */
    private array $exams = [
        [
            'name' => 'Ujian TPA Gelombang I',
            'date' => '2026-08-01',
            'session' => 'Jam 08:00',
            'room' => 'Lab A-01',
        ],
        [
            'name' => 'Tes Bahasa Inggris Bilingual',
            'date' => '2026-08-03',
            'session' => 'Jam 11:00',
            'room' => 'Lab A-02',
        ],
        [
            'name' => 'Wawancara Akademik',
            'date' => '2026-08-05',
            'session' => 'Jam 08:00',
            'room' => 'Hall C',
        ],
        [
            'name' => 'Tes Minat & Bakat',
            'date' => '2026-08-07',
            'session' => 'Jam 14:00',
            'room' => 'Library W',
        ],
    ];

    public function run(): void
    {
        foreach ($this->exams as $exam) {
            CbtExam::firstOrCreate([
                'name' => $exam['name'],
                'date' => $exam['date'],
            ], $exam);
        }
    }
}
