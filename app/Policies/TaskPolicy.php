<?php

namespace App\Policies;


use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Semua user aktif yang sudah login boleh melihat daftar tugas.
     */
    public function viewAny(User $user): bool
    {
        return $user->status === 'aktif';
    }

    /**
     * Semua user aktif boleh melihat detail tugas.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->status === 'aktif';
    }

    /**
     * Hanya superadmin yang boleh membuat tugas.
     */
    public function create(User $user): bool
    {
        return $user->status === 'aktif'
            && $user->role === 'superadmin';
    }

    /**
     * Hanya superadmin yang boleh mengubah tugas.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->status === 'aktif'
            && $user->role === 'superadmin';
    }

    /**
     * Hanya superadmin yang boleh menghapus tugas.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->status === 'aktif'
            && $user->role === 'superadmin';
    }

    /**
     * Hanya superadmin yang boleh mengelola penugasan staff.
     */
    public function assign(User $user, Task $task): bool
    {
        return $user->status === 'aktif'
            && $user->role === 'superadmin';
    }

    /**
     * Staff hanya boleh mengirim progress pada tugas yang ditugaskan kepadanya.
     * Superadmin boleh mengirim progress pada tugas mana pun.
     */
    public function createProgress(User $user, Task $task): bool
    {
        if ($user->status !== 'aktif') {
            return false;
        }

        return $user->role === 'staff'
            && $task->assignedUsers()
                ->where('users.id', $user->id)
                ->exists();
    }
}