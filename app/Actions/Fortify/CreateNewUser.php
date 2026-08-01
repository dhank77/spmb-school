<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'nisn' => ['required', 'string', 'size:10', 'unique:users'],
            'nik' => ['required', 'string', 'size:16', 'unique:users'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:male,female'],
            'program' => ['required', 'string'],
            'whatsapp_number' => ['required', 'string', 'max:20'],
            'previous_school' => ['required', 'string', 'max:255'],
            'graduation_year' => ['required', 'integer', 'min:2000', 'max:'.(date('Y') + 1)],
            'document_identity' => ['nullable', 'string'],
            'document_diploma' => ['nullable', 'string'],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'nisn' => $input['nisn'],
            'nik' => $input['nik'],
            'birth_place' => $input['birth_place'],
            'birth_date' => $input['birth_date'],
            'gender' => $input['gender'],
            'program' => $input['program'],
            'whatsapp_number' => $input['whatsapp_number'] ?? null,
            'previous_school' => $input['previous_school'],
            'graduation_year' => $input['graduation_year'],
            'document_identity' => $input['document_identity'] ?? null,
            'document_diploma' => $input['document_diploma'] ?? null,
        ]);

        $user->assignRole('student');

        return $user;
    }
}
