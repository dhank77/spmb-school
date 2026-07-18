<?php

namespace Database\Seeders;

use App\Models\AdmissionWave;
use Illuminate\Database\Seeder;

class AdmissionWaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdmissionWave::create([
            'name' => 'Gelombang 1',
            'period' => 'Januari - Maret 2024',
            'registration_cost' => 250000,
            'total_quota' => 120,
            'remaining_quota' => 0,
            'status' => 'closed',
            'sort_order' => 1,
        ]);

        AdmissionWave::create([
            'name' => 'Gelombang 2',
            'period' => 'April - Juni 2024',
            'registration_cost' => 350000,
            'total_quota' => 120,
            'remaining_quota' => 42,
            'status' => 'active',
            'sort_order' => 2,
        ]);

        AdmissionWave::create([
            'name' => 'Gelombang 3',
            'period' => 'Juli - Agustus 2024',
            'registration_cost' => 500000,
            'total_quota' => 150,
            'remaining_quota' => 150,
            'status' => 'upcoming',
            'sort_order' => 3,
        ]);
    }
}
