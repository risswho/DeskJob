<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'update',
            $this->route('task')
        ) ?? false;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'penanggung_jawab' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('status', 'aktif')
                        ->where('role',['staff', 'superadmin']);
                }),
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'target_selesai' => [
                'required',
                'date',
            ],

            'deskripsi' => [
                'required',
                'string',
            ],

            'status' => [
                'required',
                Rule::in([
                    'belum_mulai',
                    'berjalan',
                    'selesai',
                    'tertunda',
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori tugas wajib dipilih.',
            'category_id.exists' => 'Kategori tidak ditemukan.',
            'penanggung_jawab.required' => 'Penanggung jawab wajib dipilih.',
            'penanggung_jawab.exists' => 'Penanggung jawab harus superadmin aktif.',
            'nama.required' => 'Nama tugas wajib diisi.',
            'nama.max' => 'Nama tugas maksimal 255 karakter.',
            'target_selesai.required' => 'Target selesai wajib diisi.',
            'target_selesai.date' => 'Format tanggal tidak valid.',
            'deskripsi.required' => 'Deskripsi tugas wajib diisi.',
            'status.required' => 'Status tugas wajib dipilih.',
            'status.in' => 'Status tugas tidak valid.',
        ];
    }
}