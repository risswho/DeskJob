<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\AssignTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskAssignmentService;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    //daftar seluruh tugas
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Task::class);

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => [
                'nullable',
                Rule::in([
                    'belum_mulai',
                    'berjalan',
                    'selesai',
                    'tertunda',
                ]),
            ],
            'category' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],
        ]);

        $tasks = $this->taskService->getAll($filters);

        $categories = Category::query()
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return view('tasks.index', compact('tasks', 'categories', 'filters'));
    }

    //form pembuatan tugas
    public function create(): View
    {
        $this->authorize('create', Task::class);

        $categories = Category::query()
            ->orderBy('nama')
            ->get();

        $penanggungJawabs = User::query()
            ->whereIn('role', ['staff', 'superadmin'])
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        return view('tasks.create', compact(
            'categories',
            'penanggungJawabs'
        ));
    }

    //menyimpan tugas baru
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $task = $this->taskService->create(
            $request->validated()
        );

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tugas berhasil dibuat.');
    }

    //detail tugas
    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        $task = $this->taskService->getById($task);

        $staffs = collect();

        if (auth()->user()->role === 'superadmin') {
            $staffs = User::query()
                ->where('role', 'staff')
                ->where('status', 'aktif')
                ->orderBy('nama')
                ->get(['id', 'nama', 'jabatan']);
        }

        return view('tasks.show', compact('task', 'staffs'));
    }

    //form edit tugas
    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        $categories = Category::query()
            ->orderBy('nama')
            ->get();

        $penanggungJawabs = User::query()
            ->where('role',['staff', 'superadmin'])
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        return view('tasks.edit', compact(
            'task',
            'categories',
            'penanggungJawabs'
        ));
    }

    //memperbarui tugas
    public function update(
        UpdateTaskRequest $request,
        Task $task
    ): RedirectResponse {
        $this->taskService->update(
            $task,
            $request->validated()
        );

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    //menghapus tugas
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->delete($task);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    // Mengatur staff yang ditugaskan pada tugas
public function assignStaff(
    AssignTaskRequest $request,
    Task $task,
    TaskAssignmentService $assignmentService
): RedirectResponse {
    $assignmentService->sync(
        $task,
        $request->validated()['staff'],
        $request->validated()['tanggal_penugasan']
    );

    return redirect()
        ->route('tasks.show', $task)
        ->with('success', 'Penugasan staff berhasil diperbarui.');
}
}
