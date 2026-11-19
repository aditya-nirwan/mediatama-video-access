<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:65536'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'duration_seconds' => ['nullable', 'integer', 'min:1', 'max:86400'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimetypes' => 'File harus berupa video MP4 atau WebM.',
            'file.max' => 'Ukuran video maksimal 64 MB.',
        ];
    }
}
