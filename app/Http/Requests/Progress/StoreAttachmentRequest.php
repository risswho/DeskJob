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
            'attachments.min' => 'Minimal harus ada satu file.',
            'attachments.max' => 'Maksimal 5 file dalam satu kali upload.',
            'attachments.*.mimes' => 'Format file tidak didukung.',
            'attachments.*.max' => 'Ukuran setiap file maksimal 5 MB.',
        ];
    }
}