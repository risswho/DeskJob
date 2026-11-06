<?php

namespace App\Policies;


use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->status === 'aktif';
    }

    public function view(User $user, Category $category): bool
    {
        return $user->status === 'aktif';
    }

    public function create(User $user): bool
    {
        return $user->status === 'aktif'
            && $user->role === 'superadmin';
    }

    public function update(User $user, Category $category): bool
    {
        return $user->status === 'aktif'
            && $user->role === 'superadmin';
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->status === 'aktif'
            && $user->role === 'superadmin';
    }
}