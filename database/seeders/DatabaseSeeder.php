<?php

namespace Database\Seeders;


use App\Models\Category;
use App\Models\Progress;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================
        // 1. SUPERADMIN
        // =====================================

        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Super Administrator',
                'nip' => '1000000001',
                'password' => 'password',
                'jabatan' => 'Administrator',
                'role' => 'superadmin',
                'status' => 'aktif',
            ]
        );

        // =====================================
        // 2. VIEWER
        // =====================================

        $viewers = collect();

        for ($i = 1; $i <= 2; $i++) {
            $viewers->push(
                User::updateOrCreate(
                    ['username' => "viewer{$i}"],
                    [
                        'nama' => "Viewer {$i}",
                        'nip' => '200000000' . $i,
                        'password' => 'password',
                        'jabatan' => 'Pengamat',
                        'role' => 'viewer',
                        'status' => 'aktif',
                    ]
                )
            );
        }

        // =====================================
        // 3. STAFF
        // =====================================

        $staffs = collect();

        for ($i = 1; $i <= 5; $i++) {
            $staffs->push(
                User::updateOrCreate(
                    ['username' => "staff{$i}"],
                    [
                        'nama' => "Staff {$i}",
                        'nip' => '300000000' . $i,
                        'password' => 'password',
                        'jabatan' => 'Staff Pelaksana',
                        'role' => 'staff',
                        'status' => 'aktif',
                    ]
                )
            );
        }

        // =====================================
        // 4. KATEGORI
        // =====================================

        $categoryNames = [
            'Administrasi',
            'Pengembangan Sistem',
            'Infrastruktur',
            'Pemeliharaan',
        ];

        $categories = collect();

        foreach ($categoryNames as $name) {
            $categories->push(
                Category::updateOrCreate(
                    ['nama' => $name],
                    [
                        'deskripsi' => "Kategori tugas {$name}.",
                    ]
                )
            );
        }

        // =====================================
        // 5. TUGAS
        // Hanya superadmin yang menjadi creator.
        // =====================================

        $taskNames = [
            'Pembaruan Sistem Informasi',
            'Pendataan Aset Kantor',
            'Pemeliharaan Server',
            'Pembuatan Dashboard',
            'Audit Data Pengguna',
            'Perbaikan Jaringan',
            'Dokumentasi Sistem',
            'Migrasi Database',
            'Evaluasi Aplikasi',
            'Pembaruan Dokumentasi',
        ];

        $statuses = [
            'belum_mulai',
            'berjalan',
            'selesai',
            'tertunda',
        ];

        $tasks = collect();

        foreach ($taskNames as $index => $name) {
            $task = Task::firstOrNew([
                'nama' => $name,
            ]);

            $task->category_id = $categories[$index % 4]->id;
            $task->penanggung_jawab = $staffs[$index % 5]->id;
            $task->created_by = $admin->id;
            $task->target_selesai = now()->addDays($index + 7)->toDateString();
            $task->deskripsi = "Deskripsi pekerjaan untuk {$name}.";
            $task->status = $statuses[$index % 4];

            $task->save();

            $tasks->push($task);

            // Setiap tugas diberikan kepada dua staff.
            $firstStaff = $staffs[$index % 5];
            $secondStaff = $staffs[($index + 1) % 5];

            $task->assignedUsers()->syncWithoutDetaching([
                $firstStaff->id => [
                    'tanggal_penugasan' => now()->toDateString(),
                ],
                $secondStaff->id => [
                    'tanggal_penugasan' => now()->toDateString(),
                ],
            ]);
        }

        // =====================================
        // 6. PROGRESS
        // =====================================

        foreach ($tasks as $index => $task) {
            $staff = $staffs[$index % 5];

            Progress::firstOrCreate(
                [
                    'task_id' => $task->id,
                    'user_id' => $staff->id,
                    'nama' => "Progress awal {$task->nama}",
                ],
                [
                    'kendala' => null,
                    'tanggal' => now()->toDateString(),
                ]
            );
        }
    }
}