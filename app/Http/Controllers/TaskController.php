<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        // Search by title
        if ($request->has('title') && !empty($request->title)) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Filter by priority
        if ($request->has('priority') && !empty($request->priority)) {
            $query->where('priority', $request->priority);
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        return $query->paginate(5);
    }

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        $data['description'] = $request->input('description', null);
        $task = Task::create($data);
        return response()->json($task, 201);
    }

    public function show($id)
    {
        $task = Task::find($id);
        return $task ? response()->json($task) : response()->json(['error' => 'Task not found'], 404);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::find($id);
        if (!$task) return response()->json(['error' => 'Task not found'], 404);

        $task->update($request->only(['title', 'priority', 'due_date', 'status', 'description']));
        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) return response()->json(['error' => 'Task not found'], 404);

        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
