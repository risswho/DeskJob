<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'assign',
            $this->route('task')
        ) ?? false;
    }

    public function rules(): array
    {
        return [
            'staff' => [
                'required',
                'array',
                'min:1',
            ],

            'staff.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where(function ($query) {
                        $query->where('role', 'staff')
                            ->where('status', 'aktif');
                    }),
            ],

            'tanggal_penugasan' => [
                'required',
                'date',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'staff.required' => 'Pilih minimal satu staff.',
            'staff.array' => 'Format data staff tidak valid.',
            'staff.min' => 'Minimal satu staff harus dipilih.',
            'staff.*.exists' => 'Staff tidak ditemukan atau sudah nonaktif.',
            'staff.*.distinct' => 'Staff yang sama tidak boleh dipilih dua kali.',
            'tanggal_penugasan.required' => 'Tanggal penugasan wajib diisi.',
            'tanggal_penugasan.date' => 'Format tanggal penugasan tidak valid.',
        ];
    }
}
