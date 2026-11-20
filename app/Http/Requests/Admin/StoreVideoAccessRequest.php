<?php

namespace App\Http\Requests\Admin;

use App\Support\AccessDuration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVideoAccessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'video_id' => ['required', 'exists:videos,id'],
            'duration' => ['required', Rule::in([...AccessDuration::keys(), 'custom'])],
            'expires_at' => ['nullable', 'required_if:duration,custom', 'date', 'after:now'],
        ];
    }
}