<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'status' => ['required','in:planned,in_progress,done'],
            'due_date' => ['nullable','date'],
            'assignee_id' => ['nullable','integer','exists:users,id'],
            'attachment' => ['nullable','file','max:10240'], // 10MB
        ];
    }
}
