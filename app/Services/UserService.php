<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function getAll(): Collection
    {
        return User::query()
            ->orderBy('nama')
            ->get();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $this->preventSelfDeactivation($user, $data);

        // Password kosong berarti password lama dipertahankan.
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return $user->refresh();
    }

    public function delete(User $user): void
    {
        if (Auth::id() === $user->id) {
            throw ValidationException::withMessages([
                'user' => 'Kamu tidak dapat menghapus akun sendiri.',
            ]);
        }

        $hasHistory = 
            $user->createdTasks()->exists()
            || $user->responsibleTasks()->exists()
            || $user->assignedTasks()->exists()
            || $user->progress()->exists();

        if ($hasHistory) {
            throw ValidationException::withMessages([
            'user' => 'User masih memiliki riwayat tugas atau progress. Nonaktifkan akun jika tidak ingin user tersebut dapat login.',
            ]);
        }
        $user->delete();
    }

    private function preventSelfDeactivation(
        User $user,
        array $data
    ): void {
        if (Auth::id() !== $user->id) {
            return;
        }

        $newRole = $data['role'] ?? $user->role;
        $newStatus = $data['status'] ?? $user->status;

        if($newRole !== 'superadmin' || $newStatus !== 'aktif') {
            throw ValidationException::withMessages([
                'status' => 'Kamu tidak dapat menonaktifkan akun sendiri.',
            ]);
        }
    }
}
