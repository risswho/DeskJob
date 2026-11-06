<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'penanggung_jawab',
        'nama',
        'target_selesai',
        'deskripsi',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'target_selesai' => 'date',
        ];
    }

    // Kategori tugas
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Superadmin yang membuat tugas
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Penanggung jawab / PM
    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penanggung_jawab');
    }

    // Staff yang ditugaskan mengerjakan tugas
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'task_user',
            'task_id',
            'user_id'
        )
            ->withPivot('tanggal_penugasan')
            ->withTimestamps();
    }

    // Riwayat progress tugas
    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }
}
