<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at' => now(),
        ]);
    }

    /**
     * Create a student applicant with admission fields populated.
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'registration_number' => sprintf('HT%s-%03d', date('y').'01', fake()->unique()->numberBetween(1, 999)),
            'nisn' => fake()->unique()->numerify('##########'),
            'nik' => fake()->unique()->numerify('################'),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-18 years', '-12 years'),
            'gender' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'program' => fake()->randomElement(['IPA', 'IPS', 'Bahasa']),
            'previous_school' => 'SMP '.fake()->city(),
            'graduation_year' => (int) date('Y'),
            'verification_status' => fake()->randomElement([
                'tersimpan',
                'menunggu_verifikasi',
                'terverifikasi',
                'ditolak',
            ]),
        ]);
    }

    /**
     * Student with a specific verification status.
     */
    public function withStatus(string $status): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => $status,
        ]);
    }

    /**
     * Student with incomplete documents (some fields null).
     */
    public function withIncompleteDocuments(): static
    {
        return $this->state(fn (array $attributes) => [
            'document_identity' => null,
            'document_diploma' => null,
            'verification_notes' => fake()->randomElement(['MISSING PHOTO', 'MISSING DOCUMENT', 'INCOMPLETE DATA']),
        ]);
    }

    /**
     * Student rejected with a reason.
     */
    public function rejected(string $reason = 'Incomplete Academic Transcript'): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'ditolak',
            'verification_notes' => $reason,
        ]);
    }
}
