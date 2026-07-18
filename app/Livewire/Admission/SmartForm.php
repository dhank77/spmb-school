<?php

namespace App\Livewire\Admission;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class SmartForm extends Component
{
    use WithFileUploads;

    public $currentStep = 1;

    // Step 1
    public $name = '';
    public $nisn = '';
    public $nik = '';
    public $birth_place = '';
    public $birth_date = '';
    public $gender = '';
    public $program = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    // Step 2
    public $previous_school = '';
    public $graduation_year = '';

    // Step 3
    public $document_identity;
    public $document_diploma;

    public function mount()
    {
        if (Auth::check()) {
            $this->redirectRoute('dashboard');
        }
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'nisn' => ['required', 'string', 'size:10', 'unique:users'],
                'nik' => ['required', 'string', 'size:16', 'unique:users'],
                'birth_place' => ['required', 'string', 'max:255'],
                'birth_date' => ['required', 'date'],
                'gender' => ['required', 'string', 'in:male,female'],
                'program' => ['required', 'string'],
            ]);
            $this->currentStep = 2;
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'previous_school' => ['required', 'string', 'max:255'],
                'graduation_year' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            ]);
            $this->currentStep = 3;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submitForm(CreateNewUser $creator)
    {
        $this->validate([
            'document_identity' => ['required', 'image', 'max:2048'], // 2MB Max
            'document_diploma' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB Max
        ]);

        $identityPath = $this->document_identity->store('documents/identity', 'public');
        $diplomaPath = $this->document_diploma->store('documents/diploma', 'public');

        $user = $creator->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'nisn' => $this->nisn,
            'nik' => $this->nik,
            'birth_place' => $this->birth_place,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'program' => $this->program,
            'previous_school' => $this->previous_school,
            'graduation_year' => $this->graduation_year,
            'document_identity' => $identityPath,
            'document_diploma' => $diplomaPath,
        ]);

        Auth::login($user);
        
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.admission.smart-form');
    }
}
