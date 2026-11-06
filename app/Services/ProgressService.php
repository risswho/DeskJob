<?php

namespace App\Services;

use App\Models\Progress;
use App\Models\ProgressAttachment;
use App\Models\Task;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProgressService
{
    private const MAX_ATTACHMENTS = 5;

    /**
     * Membuat progress beserta lampirannya.
     */
    public function create(
        Task $task,
        array $data,
        array $attachments = []
    ): Progress {
        $this->validateAttachmentLimit(count($attachments));

        $storedFiles = [];

        try {
            $progress = DB::transaction(function () use (
                $task,
                $data,
                $attachments,
                &$storedFiles
            ) {
                $progress = Progress::create([
                    'task_id' => $task->id,
                    'user_id' => Auth::id(),
                    'nama' => $data['nama'],
                    'kendala' => $data['kendala'] ?? null,
                    'tanggal' => $data['tanggal'],
                ]);

                foreach ($attachments as $file) {
                    if (!$file instanceof UploadedFile) {
                        continue;
                    }

                    $path = $file->store(
                        'progress-attachments',
                        'public'
                    );

                    if (!$path) {
                        throw new \RuntimeException(
                            'Gagal menyimpan lampiran.'
                        );
                    }

                    $storedFiles[] = $path;

                    ProgressAttachment::create([
                        'progress_id' => $progress->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }

                return $progress;
            });

            return $progress->load('attachments');

        } catch (Throwable $exception) {
            Storage::disk('public')->delete($storedFiles);

            throw $exception;
        }
    }

    /**
     * Memperbarui informasi progress.
     */
    public function update(
        Progress $progress,
        array $data
    ): Progress {
        $progress->update([
            'nama' => $data['nama'],
            'kendala' => $data['kendala'] ?? null,
            'tanggal' => $data['tanggal'],
        ]);

        return $progress->refresh();
    }

    /**
     * Menghapus progress beserta seluruh lampirannya.
     */
    public function delete(Progress $progress): void
    {
        $filePaths = DB::transaction(function () use ($progress) {
            $filePaths = $progress->attachments()
                ->pluck('file_path')
                ->all();

            $progress->delete();

            return $filePaths;
        });

        // File dihapus setelah transaksi database berhasil.
        Storage::disk('public')->delete($filePaths);
    }

    /**
     * Menambahkan lampiran ke progress yang sudah ada.
     */
    public function addAttachments(
        Progress $progress,
        array $attachments
    ): Progress {
        $storedFiles = [];

        try {
            $updatedProgress = DB::transaction(function () use (
                $progress,
                $attachments,
                &$storedFiles
            ) {
                $currentCount = $progress->attachments()->count();

                $this->validateAttachmentLimit(
                    $currentCount + count($attachments)
                );

                foreach ($attachments as $file) {
                    if (!$file instanceof UploadedFile) {
                        continue;
                    }

                    $path = $file->store(
                        'progress-attachments',
                        'public'
                    );

                    if (!$path) {
                        throw new \RuntimeException(
                            'Gagal menyimpan lampiran.'
                        );
                    }

                    $storedFiles[] = $path;

                    ProgressAttachment::create([
                        'progress_id' => $progress->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }

                return $progress;
            });

            return $updatedProgress->load('attachments');

        } catch (Throwable $exception) {
            Storage::disk('public')->delete($storedFiles);

            throw $exception;
        }
    }

    /**
     * Menghapus satu lampiran.
     */
    public function deleteAttachment(
        ProgressAttachment $attachment
    ): void {
        $filePath = DB::transaction(function () use ($attachment) {
            $filePath = $attachment->file_path;

            $attachment->delete();

            return $filePath;
        });

        // Hapus file setelah data database berhasil dihapus.
        Storage::disk('public')->delete($filePath);
    }

    /**
     * Memastikan jumlah lampiran tidak melebihi batas.
     */
    private function validateAttachmentLimit(int $total): void
    {
        if ($total > self::MAX_ATTACHMENTS) {
            throw ValidationException::withMessages([
                'attachments' => 'Maksimal 5 lampiran untuk setiap progress.',
            ]);
        }
    }
}
