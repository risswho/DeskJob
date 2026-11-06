<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskAssignmentService
{
    //mengatur staff yang digunakan pada suatu tugas
    public function sync(
        Task $task,
        array $staffIds,
        string $tanggalPenugasan
    ): void {
        $assignments = [];

        foreach ($staffIds as $staffid) {
            $assignments[$staffid] = [
                'tanggal_penugasan' => $tanggalPenugasan,
            ];
        }

        DB::transaction(function () use ($task, $assignments) {
            $task->assignedUsers()->sync($assignments);
        });
    }
}

//Kenapa pakai transaksi database?

// Supaya perubahan penugasan dilakukan sebagai satu kesatuan. Kalau proses gagal, database tidak dibiarkan dalam kondisi setengah berubah.

// Kenapa pakai sync()?

// Misalnya sebelumnya tugas memiliki Staff 1 dan Staff 2, lalu superadmin memilih Staff 2 dan Staff 3. Hasil akhirnya:
// - Staff 1 dilepas.
// - Staff 2 tetap ditugaskan.
// - Staff 3 ditambahkan.