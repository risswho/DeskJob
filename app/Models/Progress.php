<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Progress extends Model
{
    use HasFactory;

    protected $table = 'progress';

    protected $fillable = [
        'task_id',
        'user_id',
        'nama',
        'kendala',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    // Tugas tempat progress dibuat
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    // User yang membuat progress
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Lampiran progress
    public function attachments(): HasMany
    {
        return $this->hasMany(ProgressAttachment::class);
    }
}