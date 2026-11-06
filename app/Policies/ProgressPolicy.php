<?php

namespace App\Policies;

use App\Models\Progress;
use App\Models\User;

class ProgressPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->status === 'aktif';
    }

    public function view(User $user, Progress $progress): bool
    {
        return $user->status === 'aktif';
    }

    public function create(User $user): bool
    {
        return $user->status === 'aktif'
            && in_array($user->role, ['staff', 'superadmin']);
    }

    public function update(User $user, Progress $progress): bool
    {
        if ($user->status !== 'aktif') {
            return false;
        }

        return $user->role === 'superadmin'
            || $progress->user_id === $user->id;
    }

    public function delete(User $user, Progress $progress): bool
    {
        if ($user->status !== 'aktif') {
            return false;
        }

        return $user->role === 'superadmin'
            || $progress->user_id === $user->id;
    }
}