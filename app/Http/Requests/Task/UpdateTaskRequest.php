<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title' => ['sometimes','required','string','max:255'],
            'description' => ['sometimes','nullable','string'],
            'status' => ['sometimes','required','in:planned,in_progress,done'],
            'due_date' => ['sometimes','nullable','date'],
            'assignee_id' => ['sometimes','nullable','integer','exists:users,id'],
            'attachment' => ['sometimes','nullable','file','max:10240'],
        ];
    }
}
