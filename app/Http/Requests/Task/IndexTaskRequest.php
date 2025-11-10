<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class IndexTaskRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'status' => ['nullable','in:planned,in_progress,done'],
            'assignee_id' => ['nullable','integer','exists:users,id'],
            'due_date' => ['nullable','date'],
            'due_date_from' => ['nullable','date'],
            'due_date_to' => ['nullable','date','after_or_equal:due_date_from'],
            'per_page' => ['nullable','integer','min:1','max:100'],
        ];
    }
}
