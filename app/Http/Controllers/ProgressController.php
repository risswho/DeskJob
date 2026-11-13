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
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    /**
     * Menampilkan lampiran progress.
     */
    public function showAttachment(
        ProgressAttachment $attachment
    )
    {
        // Periksa izin melihat progress.
        $this->authorize('view', $attachment->progress);

        $disk = Storage::disk('local');

        // Periksa apakah file tersedia.
        abort_unless(
            $disk->exists($attachment->file_path),
            404,
            'File tidak ditemukan.'
        );

        //ambil lokasi fisik file
        $path = $disk->path($attachment->file_path);

    //tentukan mime type
        $mimeType = $disk->mimeType($attachment->file_path);

        //kirim file agar ditampilkan browsser
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Mengunduh lampiran progress.
     */
    public function downloadAttachment(
        ProgressAttachment $attachment
    )
    {
        // Periksa izin melihat progress.
        $this->authorize('view', $attachment->progress);

        $disk = Storage::disk('local');

        // Periksa apakah file tersedia.
        abort_unless(
            $disk->exists($attachment->file_path),
            404,
            'File tidak ditemukan.'
        );

        //ambil lokasi fisik file
        $path = $disk->path($attachment->file_path);

        //kirim file agar langsung diunduh browser
        return response()->download($path, $attachment->file_name, [
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
