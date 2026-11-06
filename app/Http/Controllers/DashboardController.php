<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Progress;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statusCounts = Task::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'total' => Task::count(),
            'belum_mulai' => (int) ($statusCounts['belum_mulai'] ?? 0),
            'berjalan' => (int) ($statusCounts['berjalan'] ?? 0),
            'selesai' => (int) ($statusCounts['selesai'] ?? 0),
            'tertunda' => (int) ($statusCounts['tertunda'] ?? 0),
            'terlambat' => Task::query()
                ->whereDate('target_selesai', '<', today())
                ->where('status', '!=', 'selesai')
                ->count(),
            'kategori' => Category::count(),
            'pengguna' => User::query()
                ->where('status', 'aktif')
                ->count(),
        ];

        $recentTasks = Task::query()
            ->with([
                'category:id,nama',
                'penanggungJawab:id,nama',
            ])
            ->withCount('progress')
            ->latest()
            ->take(5)
            ->get();

        $upcomingTasks = Task::query()
            ->with([
                'category:id,nama',
                'penanggungJawab:id,nama',
            ])
            ->where('status', '!=', 'selesai')
            ->whereDate('target_selesai', '>=', today())
            ->orderBy('target_selesai')
            ->take(5)
            ->get();

        $recentProgress = Progress::query()
            ->with([
                'user:id,nama',
                'task:id,nama',
            ])
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'recentTasks',
            'upcomingTasks',
            'recentProgress'
        ));
    }
}
