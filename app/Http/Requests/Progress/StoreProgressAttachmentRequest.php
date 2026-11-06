<?php

namespace App\Http\Requests\Progress;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'update',
            $this->route('progress')
        ) ?? false;
    }

    public function rules(): array
    {
        return [
            'attachments' => [
                'required',
                'array',
                'min:1',
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

    public function messages(): array
    {
        return [
            'attachments.required' => 'Lampiran wajib diisi.',
            'attachments.array' => 'Lampiran harus berupa kumpulan file.',
            'attachments.min' => 'Minimal 1 lampiran.',
            'attachments.max' => 'Maksimal 5 lampiran dalam satu upload.',

            'attachments.*.required' => 'Setiap lampiran wajib diisi.',
            'attachments.*.file' => 'Lampiran harus berupa file yang valid.',
            'attachments.*.mimes' => 'Format file tidak didukung.',
            'attachments.*.max' => 'Ukuran setiap file maksimal 5 MB.',
        ];
    }
}
