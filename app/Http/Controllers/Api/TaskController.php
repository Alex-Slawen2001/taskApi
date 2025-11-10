<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\IndexTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Mail\TaskCreatedMail;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function index(IndexTaskRequest $request, Project $project)
    {
        $filters = $request->validated();

        $query = Task::query()->where('project_id', $project->id);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['assignee_id'])) {
            $query->where('assignee_id', $filters['assignee_id']);
        }
        if (!empty($filters['due_date'])) {
            $query->whereDate('due_date', $filters['due_date']);
        }
        if (!empty($filters['due_date_from'])) {
            $query->whereDate('due_date', '>=', $filters['due_date_from']);
        }
        if (!empty($filters['due_date_to'])) {
            $query->whereDate('due_date', '<=', $filters['due_date_to']);
        }

        $perPage = $filters['per_page'] ?? 15;
        $tasks = $query->latest('id')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => TaskResource::collection($tasks),
            'meta' => [
                'current_page' => $tasks->currentPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'last_page' => $tasks->lastPage(),
            ],
        ]);
    }

    public function store(StoreTaskRequest $request, Project $project)
    {
        $data = $request->validated();
        $data['project_id'] = $project->id;

        $task = Task::create($data);

        if ($request->hasFile('attachment')) {
            $task->clearMediaCollection('attachments');
            $task->addMediaFromRequest('attachment')->toMediaCollection('attachments');
        }

        if ($task->assignee && $task->assignee->email) {
            Mail::to($task->assignee->email)->send(new TaskCreatedMail($task));
        }

        return response()->json([
            'success' => true,
            'message' => 'Task created',
            'data' => new TaskResource($task->fresh(['assignee', 'project'])),
        ], 201);
    }

    public function show(Task $task)
    {
        return response()->json([
            'success' => true,
            'data' => new TaskResource($task->load(['assignee', 'project'])),
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated();

        unset($data['project_id']);

        $task->update($data);

        if ($request->hasFile('attachment')) {
            $task->clearMediaCollection('attachments');
            $task->addMediaFromRequest('attachment')->toMediaCollection('attachments');
        }

        return response()->json([
            'success' => true,
            'message' => 'Task updated',
            'data' => new TaskResource($task->fresh(['assignee', 'project'])),
        ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted',
        ], 200);
    }
}
