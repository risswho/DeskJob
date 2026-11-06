<?php

namespace App\Http\Controllers;

use App\Http\Requests\Progress\StoreAttachmentRequest;
use App\Http\Requests\Progress\StoreProgressRequest;
use App\Http\Requests\Progress\UpdateProgressRequest;
use App\Models\Progress;
use App\Models\ProgressAttachment;
use App\Models\Task;
use App\Services\ProgressService;
use Illuminate\Http\RedirectResponse;

class ProgressController extends Controller
{
    public function store(
        StoreProgressRequest $request,
        Task $task,
        ProgressService $progressService
    ): RedirectResponse {
        $data = $request->validated();

        $attachments = $data['attachments'] ?? [];

        unset($data['attachments']);

        $progressService->create($task, $data, $attachments);

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Progress berhasil ditambahkan.');
    }

    public function update(
        UpdateProgressRequest $request,
        Progress $progress,
        ProgressService $progressService
    ): RedirectResponse {
        $progressService->update($progress, $request->validated());

        return redirect()
            ->route('tasks.show', $progress->task_id)
            ->with('success', 'Progress berhasil diperbarui.');
    }

    public function destroy(
        Progress $progress,
        ProgressService $progressService
    ): RedirectResponse {
        $this->authorize('delete', $progress);

        $taskId = $progress->task_id;

        $progressService->delete($progress);

        return redirect()
            ->route('tasks.show', $taskId)
            ->with('success', 'Progress berhasil dihapus.');
    }

    public function addAttachments(
        StoreAttachmentRequest $request,
        Progress $progress,
        ProgressService $progressService
    ): RedirectResponse {
        $progressService->addAttachments(
            $progress,
            $request->validated()['attachments']
        );

        return redirect()
            ->route('tasks.show', $progress->task_id)
            ->with('success', 'Lampiran berhasil ditambahkan.');
    }

    public function destroyAttachment(
        ProgressAttachment $attachment,
        ProgressService $progressService
    ): RedirectResponse {
        $this->authorize('delete', $attachment->progress);

        $taskId = $attachment->progress->task_id;

        $progressService->deleteAttachment($attachment);

        return redirect()
            ->route('tasks.show', $taskId)
            ->with('success', 'Lampiran berhasil dihapus.');
    }
}

// Alur kerjanya:
// 1. StoreProgressRequest memvalidasi data progress.
// 2. StoreProgressAttachmentRequest memvalidasi lampiran.
// 3. ProgressService menyimpan progress dan file.
// 4. Pengguna diarahkan kembali ke detail tugas.