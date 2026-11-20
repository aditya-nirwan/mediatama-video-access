<?php

namespace App\Http\Requests\Admin;

use App\Support\AccessDuration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApproveAccessRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'duration' => ['required', Rule::in([...AccessDuration::keys(), 'custom'])],
            'expires_at' => ['nullable', 'required_if:duration,custom', 'date', 'after:now'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'expires_at.required_if' => 'Isi waktu berakhir kalau memilih durasi custom.',
            'expires_at.after' => 'Waktu berakhir harus setelah sekarang.',
        ];
    }
}