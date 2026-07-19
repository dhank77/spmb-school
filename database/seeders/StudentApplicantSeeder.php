<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class StudentApplicantSeeder extends Seeder
{
    /**
     * Seed student applicants with various verification statuses for the pipeline.
     */
    public function run(): void
    {
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'student']);

        // Create an admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@hitech.school'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => 'password',
            ]
        );
        $admin->assignRole('admin');

        // Create students with "tersimpan" status (saved, incomplete docs)
        User::factory()
            ->count(6)
            ->student()
            ->withStatus('tersimpan')
            ->withIncompleteDocuments()
            ->create()
            ->each(fn (User $user) => $user->assignRole('student'));

        // Create students with "tersimpan" status (saved, complete docs)
        User::factory()
            ->count(6)
            ->student()
            ->withStatus('tersimpan')
            ->create()
            ->each(fn (User $user) => $user->assignRole('student'));

        // Create students with "menunggu_verifikasi" status
        User::factory()
            ->count(28)
            ->student()
            ->withStatus('menunggu_verifikasi')
            ->create()
            ->each(fn (User $user) => $user->assignRole('student'));

        // Create students with "terverifikasi" status
        User::factory()
            ->count(20)
            ->student()
            ->withStatus('terverifikasi')
            ->create()
            ->each(fn (User $user) => $user->assignRole('student'));

        // Create students with "ditolak" status
        User::factory()
            ->count(2)
            ->student()
            ->rejected('Incomplete Academic Transcript')
            ->create()
            ->each(fn (User $user) => $user->assignRole('student'));

        User::factory()
            ->count(2)
            ->student()
            ->rejected('Falsified Documents')
            ->create()
            ->each(fn (User $user) => $user->assignRole('student'));
    }
}
