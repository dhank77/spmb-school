<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('components.layouts.admin-portal')]
class Pipeline extends Component
{
    public string $search = '';

    public ?int $selectedStudentId = null;

    public bool $showStudentDetail = false;

    public string $verificationNotes = '';

    /**
     * Pipeline status columns configuration.
     *
     * @var array<int, array{key: string, label: string, color: string}>
     */
    public array $columns = [
        ['key' => 'tersimpan', 'label' => 'TERSIMPAN', 'color' => 'bg-outline'],
        ['key' => 'menunggu_verifikasi', 'label' => 'MENUNGGU VERIFIKASI', 'color' => 'bg-secondary-container'],
        ['key' => 'terverifikasi', 'label' => 'TERVERIFIKASI', 'color' => 'bg-primary'],
        ['key' => 'ditolak', 'label' => 'DITOLAK', 'color' => 'bg-error'],
    ];

    /**
     * Open the student detail modal.
     */
    #[On('view-student')]
    public function viewStudent(int $userId): void
    {
        $this->selectedStudentId = $userId;
        $this->verificationNotes = User::students()->findOrFail($userId)->verification_notes ?? '';
        $this->showStudentDetail = true;
    }

    /**
     * Close the student detail modal.
     */
    public function closeStudent(): void
    {
        $this->showStudentDetail = false;
        $this->selectedStudentId = null;
        $this->verificationNotes = '';
    }

    /**
     * Update verification status from the detail modal.
     */
    public function changeVerificationStatus(string $newStatus): void
    {
        if ($this->selectedStudentId === null) {
            return;
        }

        $validStatuses = ['tersimpan', 'menunggu_verifikasi', 'terverifikasi', 'ditolak'];

        if (! in_array($newStatus, $validStatuses)) {
            return;
        }

        $student = User::students()->findOrFail($this->selectedStudentId);
        $student->update([
            'verification_status' => $newStatus,
            'verification_notes' => $this->verificationNotes,
        ]);

        $this->showStudentDetail = false;
        $this->selectedStudentId = null;
        $this->verificationNotes = '';
    }

    /**
     * Update the verification status of a student (used by drag-and-drop).
     */
    public function updateStatus(int $userId, string $newStatus): void
    {
        $validStatuses = ['tersimpan', 'menunggu_verifikasi', 'terverifikasi', 'ditolak'];

        if (! in_array($newStatus, $validStatuses)) {
            return;
        }

        $user = User::students()->findOrFail($userId);
        $user->update(['verification_status' => $newStatus]);
    }

    /**
     * Sort items within a column (wire:sort handler).
     *
     * @param  array<int, array{value: string|int, order: int}>  $items
     */
    public function sort(array $items, string $status): void
    {
        $validStatuses = ['tersimpan', 'menunggu_verifikasi', 'terverifikasi', 'ditolak'];

        if (! in_array($status, $validStatuses)) {
            return;
        }

        foreach ($items as $item) {
            User::students()
                ->where('id', $item['value'])
                ->update(['verification_status' => $status]);
        }
    }

    /**
     * Get students grouped by verification status.
     *
     * @return array<string, Collection<int, User>>
     */
    protected function getStudentsByStatus(): array
    {
        $query = User::students();

        if ($this->search !== '') {
            $query->where(function ($q): void {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('registration_number', 'like', "%{$this->search}%");
            });
        }

        $students = $query->orderBy('updated_at', 'desc')->get();

        $grouped = [];
        foreach ($this->columns as $column) {
            $grouped[$column['key']] = $students->where('verification_status', $column['key'])->values();
        }

        return $grouped;
    }

    public function render(): View
    {
        $studentsByStatus = $this->getStudentsByStatus();
        $totalApplicants = User::students()->count();
        $selectedStudent = $this->selectedStudentId
            ? User::students()->find($this->selectedStudentId)
            : null;

        return view('livewire.admin.pipeline', [
            'studentsByStatus' => $studentsByStatus,
            'totalApplicants' => $totalApplicants,
            'selectedStudent' => $selectedStudent,
        ]);
    }
}
