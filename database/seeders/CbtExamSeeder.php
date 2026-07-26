<?php

namespace Database\Seeders;

use App\Models\CbtExam;
use App\Models\CbtSubject;
use Illuminate\Database\Seeder;

class CbtExamSeeder extends Seeder
{
    public function run(): void
    {
        $math = CbtSubject::where('code', 'MA')->first() ?? CbtSubject::first();
        $sc = CbtSubject::where('code', 'SC')->first() ?? CbtSubject::first();
        $en = CbtSubject::where('code', 'EN')->first() ?? CbtSubject::first();

        $today = now()->format('Y-m-d');
        $next3Days = now()->addDays(3)->format('Y-m-d');
        $next7Days = now()->addDays(7)->format('Y-m-d');

        $exams = [
            [
                'cbt_subject_id' => $math?->id,
                'name' => 'Ujian TPA Gelombang I',
                'date' => $today,
                'session' => 'Jam 08:00',
                'room' => 'Lab A-01',
            ],
            [
                'cbt_subject_id' => $en?->id,
                'name' => 'Tes Bahasa Inggris Bilingual',
                'date' => $today,
                'session' => 'Jam 11:00',
                'room' => 'Lab A-02',
            ],
            [
                'cbt_subject_id' => $sc?->id,
                'name' => 'Tes Saintek & Logika',
                'date' => $next3Days,
                'session' => 'Jam 08:00',
                'room' => 'Hall C',
            ],
            [
                'cbt_subject_id' => $math?->id,
                'name' => 'Tes Minat & Bakat',
                'date' => $next7Days,
                'session' => 'Jam 14:00',
                'room' => 'Library W',
            ],
        ];

        foreach ($exams as $exam) {
            CbtExam::updateOrCreate([
                'name' => $exam['name'],
            ], $exam);
        }
    }
}
