<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\ProjectManagement;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::query()
            ->where('project_management_id', $request->project_id)
            ->with(['creator', 'assignee', 'members'])
            ->get()
            ->groupBy('status');

        return response()->json([
            'standby' => $tasks->get(Task::STATUS_STANDBY, []),
            'ongoing' => $tasks->get(Task::STATUS_ONGOING, []),
            'done' => $tasks->get(Task::STATUS_DONE, []),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after_or_equal:today',
            'project_management_id' => 'required|exists:project_management,id',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        $task = Task::create([
            ...$validated,
            'creator_id' => auth()->id(),
            'status' => Task::STATUS_STANDBY,
        ]);

        if ($request->has('member_ids')) {
            $task->members()->sync($request->member_ids);
        }

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:standby,ongoing,done',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'priority' => 'sometimes|in:low,medium,high',
            'due_date' => 'sometimes|nullable|date',
            'assignee_id' => 'sometimes|nullable|exists:users,id',
            'member_ids' => 'sometimes|array',
            'member_ids.*' => 'exists:users,id',
        ]);

        $task->update($validated);

        if ($request->has('member_ids')) {
            $task->members()->sync($request->member_ids);
        }

        return response()->json(['message' => 'Task updated successfully']);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|in:standby,ongoing,done',
        ]);

        $task = Task::find($request->task_id);
        $task->update(['status' => $request->status]);

        return response()->json(['message' => 'Task status updated']);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }
}
