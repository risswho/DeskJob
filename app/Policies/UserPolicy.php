<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->status === 'aktif'
            && $user->role === 'superadmin';
    }

    public function view(User $user, User $targetUser): bool
    {
        return $user->status === 'aktif'
            && $user->role === 'superadmin';
    }

    public function create(User $user): bool
    {
        return $user->status === 'aktif'
            && $user->role === 'superadmin';
    }

    public function update(User $user, User $targetUser): bool
    {
        return $user->status === 'aktif'
            && $user->role === 'superadmin';
    }

    public function delete(User $user, User $targetUser): bool
    {
        return $user->status === 'aktif'
            && $user->role === 'superadmin'
            && $user->id !== $targetUser->id;
    }
}
