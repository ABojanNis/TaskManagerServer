<?php

namespace App\Http\Controllers;

use App\Helpers\TaskStatuses;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Resources\TasksResource;
use App\Task;
use App\UserTasks;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return TasksResource::collection(auth()->user()->tasks()->paginate(5));
    }

    /**
     * @param CreateTaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateTaskRequest $request)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['message' => 'You have no permission to create tasks!']);
        }
        Task::create($request->only('title', 'task', 'send_at'));

        return response()->json(['message' => 'Task successfully created!']);
    }

    /**
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsCompleted(Task $task)
    {
        $userTask = UserTasks::where('user_id', auth()->user()->id)->where('task_id', $task->id)->first();
        $userTask->status = TaskStatuses::COMPLETED;
        $userTask->save();

        return response()->json(['task' => $userTask, 'message' => 'Task marked as Completed!']);
    }

    /**
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsFailed(Task $task)
    {
        $userTask = UserTasks::where('user_id', auth()->user()->id)->where('task_id', $task->id)->first();
        $userTask->status = TaskStatuses::FAILED;
        $userTask->save();

        return response()->json(['task' => $userTask, 'message' => 'Task marked as Failed!']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMostSuccessfullyUsers()
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['message' => 'You have no permission to view most successfully users!']);
        }

        $from = Carbon::now()->startOfWeek();
        $to = Carbon::now()->endOfWeek();

        $mostSuccessfullyUsers = UserTasks::select('user_id', \DB::raw('count(user_id) as completed_tasks'), 'user_tasks.created_at', 'users.name')
            ->join('users', 'users.id', 'user_id')
            ->where('status', TaskStatuses::COMPLETED)
            ->whereBetween('user_tasks.created_at', [$from, $to])
            ->groupBy('user_id', 'users.name', 'user_tasks.created_at')
            ->orderBy('completed_tasks', 'desc')
            ->take(10)
            ->get();

        return response()->json(['most_successfully' => $mostSuccessfullyUsers]);
    }
}
