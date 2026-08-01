<?php

namespace App\Livewire\Admission;

use App\Actions\Fortify\CreateNewUser;
use App\Models\AdmissionWave;
use App\Services\FonnteService;
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

    public $whatsapp_number = '';

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
                'whatsapp_number' => ['required', 'string', 'max:20'],
            ]);
            $this->currentStep = 2;
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'previous_school' => ['required', 'string', 'max:255'],
                'graduation_year' => ['required', 'integer', 'min:2000', 'max:'.(date('Y') + 1)],
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

    public function submitForm(CreateNewUser $creator, FonnteService $fonnte)
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
            'whatsapp_number' => $this->whatsapp_number,
            'previous_school' => $this->previous_school,
            'graduation_year' => $this->graduation_year,
            'document_identity' => $identityPath,
            'document_diploma' => $diplomaPath,
        ]);

        Auth::login($user);

        // Send WhatsApp billing notification
        if ($this->whatsapp_number) {
            // Resolve base fee from the active admission wave (fallback to 0 if none active)
            $activeWave = AdmissionWave::where('status', 'active')->first();
            $baseFee = $activeWave?->registration_cost ?? 0;

            // Derive unique code from last 3 digits of the WhatsApp number
            $digits = preg_replace('/\D/', '', $this->whatsapp_number);
            $uniqueCode = ((int) substr($digits, -3)) + 4000;

            $fonnte->sendRegistrationBilling(
                whatsappNumber: $this->whatsapp_number,
                studentName: $user->name,
                registrationNumber: $user->registration_number ?? 'SPMB-'.str_pad($user->id, 6, '0', STR_PAD_LEFT),
                baseFee: $baseFee,
                uniqueCode: $uniqueCode,
            );
        }

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.admission.smart-form');
    }
}
