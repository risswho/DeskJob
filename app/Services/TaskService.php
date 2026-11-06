<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    
    //mengambil daftar tugas
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $search = trim((string) ($filters['q'] ?? ''));

        return Task::query()
            ->with([
                'category:id,nama',
                'creator:id,nama',
                'penanggungJawab:id,nama',
            ])
            ->withCount([
                'assignedUsers',
                'progress',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . mb_strtolower($search) . '%';

                $query->where(function ($query) use ($like) {
                    $query->whereRaw('LOWER(tasks.nama) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(tasks.deskripsi) LIKE ?', [$like])
                        ->orWhereHas('category', function ($query) use ($like) {
                            $query->whereRaw(
                                'LOWER(categories.nama) LIKE ?',
                                [$like]
                            );
                        })
                        ->orWhereHas('penanggungJawab', function ($query) use ($like) {
                            $query->whereRaw(
                                'LOWER(users.nama) LIKE ?',
                                [$like]
                            );
                        });
                });
            })
            ->when(
                !empty($filters['status']),
                fn ($query) => $query->where('status', $filters['status'])
            )
            ->when(
                !empty($filters['category']),
                fn ($query) => $query->where('category_id', $filters['category'])
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    //mengambil detail tugas
    public function getById(Task $task): Task
    {
        return $task->load([
            'category',
            'creator',
            'penanggungJawab',
            'assignedUsers',
            'progress.user',
            'progress.attachments',
        ]);
    }

    //membuat tugas baru
    public function create(array $data): Task
    {
        $task = new Task();

        $task->category_id = $data['category_id'];
        $task->penanggung_jawab = $data['penanggung_jawab'];
        $task->nama = $data['nama'];
        $task->target_selesai = $data['target_selesai'];
        $task->deskripsi = $data['deskripsi'];
        $task->status = $data['status'] ?? 'belum_mulai';

        //otomatis mengambil id superadmin yang sedang login
        $task->created_by = Auth::id();

        $task->save();

        return $task->refresh();
    }

    //mengubah tugas
    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task->refresh();
    }

    //menghapus tugas
    public function delete(Task $task): void
    {
        $task->delete();
    }
}