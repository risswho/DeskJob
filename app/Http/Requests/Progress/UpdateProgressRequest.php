<?php

namespace App\Http\Requests\Progress;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgressRequest extends FormRequest
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
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama progress wajib diisi.',
            'nama.max' => 'Nama progress maksimal 255 karakter.',
            'tanggal.required' => 'Tanggal progress wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
        ];
    }
}