<?php

namespace App\Livewire\Admission;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SmartForm extends Component
{
    public $currentStep = 1;

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

    public function mount()
    {
        if (Auth::check()) {
            // If already logged in, redirect to dashboard or next step
            $this->redirectRoute('dashboard');
        }
    }

    public function nextStep(CreateNewUser $creator)
    {
        if ($this->currentStep === 1) {
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
            ]);

            Auth::login($user);
            $this->currentStep = 2;
            
            // Note: For now, if they want to stay on the page for step 2, we just advance the step.
            // If the user hasn't designed step 2 yet, we can redirect to dashboard.
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.admission.smart-form');
    }
}
