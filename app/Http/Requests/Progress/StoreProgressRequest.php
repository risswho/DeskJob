<?php

namespace App\Http\Requests\Progress;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Override;

class StoreProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'createProgress',
            $this->route('task')
        ) ?? false;
    }

    public function rules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'kendala' => [
                'nullable',
                'string',
            ],

            'tanggal' => [
                'required',
                'date',
            ],

            'attachments' => [
                'nullable',
                'array',
                'max:5',
            ],

            'attachments.*' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
                'max:5120',
            ],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama progress wajib diisi.',
            'nama.max' => 'Nama progress maksimal 255 karakter.',

            'tanggal.required' => 'Tanggal progress wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',

            'attachments.array' => 'Lampiran harus berupa kumpulan file.',
            'attachments.max' => 'Maksimal 5 lampiran.',
            'attachments.*.required' => 'Setiap lampiran wajib berupa file.',
            'attachments.*.file' => 'Lampiran harus berupa file yang valid.',
            'attachments.*.mimes' => 'Format file tidak didukung.',
            'attachments.*.max' => 'Ukuran setiap file maksimal 5 MB.',
        ];
    }
}
