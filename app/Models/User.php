<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'nip',
        'username',
        'password',
        'jabatan',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Tugas yang dibuat oleh superadmin
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    // Tugas yang menjadi tanggung jawab user (PM)
    public function responsibleTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'penanggung_jawab');
    }

    // Tugas yang diberikan kepada user sebagai staff
    public function assignedTasks(): BelongsToMany
    {
        return $this->belongsToMany(
            Task::class,
            'task_user',
            'user_id',
            'task_id'
        )
            ->withPivot('tanggal_penugasan')
            ->withTimestamps();
    }

    // Progress yang dibuat user
    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }
}
