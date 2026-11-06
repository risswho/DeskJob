<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'progress_id',
        'file_path',
        'file_name',
    ];

    public function progress(): BelongsTo
    {
        return $this->belongsTo(Progress::class);
    }
}