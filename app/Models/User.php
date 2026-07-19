<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password', 'nisn', 'nik', 'birth_place', 'birth_date', 'gender', 'program', 'previous_school', 'graduation_year', 'document_identity', 'document_diploma', 'verification_status', 'registration_number', 'verification_notes'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    use HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Scope to filter only student users.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeStudents(Builder $query): Builder
    {
        return $query->role('student');
    }

    /**
     * Scope to filter by verification status.
     *
     * @param  Builder<static>  $query
     */
    public function scopeWithVerificationStatus(Builder $query, string $status): Builder
    {
        return $query->where('verification_status', $status);
    }

    /**
     * Calculate document completion percentage.
     */
    public function getDocumentProgressAttribute(): int
    {
        $fields = ['nisn', 'nik', 'document_identity', 'document_diploma', 'previous_school'];
        $filled = collect($fields)->filter(fn (string $field): bool => ! empty($this->{$field}))->count();

        return (int) round(($filled / count($fields)) * 100);
    }

    /**
     * Count filled document fields.
     */
    public function getDocumentsSubmittedAttribute(): int
    {
        $fields = ['nisn', 'nik', 'document_identity', 'document_diploma', 'previous_school'];

        return collect($fields)->filter(fn (string $field): bool => ! empty($this->{$field}))->count();
    }

    /**
     * Total number of required document fields.
     */
    public function getDocumentsTotalAttribute(): int
    {
        return 5;
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }
}
