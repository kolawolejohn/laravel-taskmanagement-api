<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskFilterRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use App\Jobs\AblyPublishJob;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Task Management API",
 *      description="API documentation for Task Management system"
 * )
 *
 * @OA\Tag(
 *     name="Tasks",
 *     description="Endpoints related to tasks"
 * )
 */

class TaskController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/tasks",
     *     tags={"Tasks"},
     *     summary="Get paginated list of tasks, can be filtered by status and search by title",
     *     @OA\Response(
     *         response=200,
     *         description="Paginated tasks response",
     *         @OA\JsonContent(ref="#/components/schemas/PaginatedTasksResponse")
     *     )
     * )
     */

    public function index(TaskFilterRequest $request)
    {
        $query = Task::orderBy('id', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $tasks = $query->paginate(5);

        return $this->showAllPaginated($tasks, "Tasks fetched successfully", 200);
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     tags={"Tasks"},
     *     summary="Create a new task",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function store(TaskStoreRequest $request)
    {
        $task = Task::create($request->validated());

        // Dispatch job to publish task.created event asynchronously
        // AblyPublishJob::dispatch('task.created', $task->toArray());
        AblyPublishJob::dispatch('task.created', [
            'id' => $task->id,
            'title' => $task->title,
        ]);

        return $this->successResponse($task, "New Task Created Successfully", 201);
    }


    /**
     * @OA\Get(
     *     path="/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Fetch a single task by ID",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task fetched successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function show(Task $task)
    {
        return $this->showOne($task, "Task fetched Successfully", 200);
    }

    /**
     * @OA\Post(
     *     path="/tasks/{$task}",
     *     tags={"Tasks"},
     *     summary="Updates an existing task",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task Updated Succesfully",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        $validatedData = $request->validated();
        $task->fill($validatedData);

        if ($task->isClean()) {
            return $this->errorResponse("You must update either title, description or status to successfully update a task", 422);
        }

        $task->update($validatedData);

        // Dispatch job for task.updated event
        // AblyPublishJob::dispatch('task.updated', $task->toArray());
        AblyPublishJob::dispatch('task.updated', [
            'id' => $task->id,
            'title' => $task->title,
        ]);

        return $this->showOne($task, "Task updated Successfully", 200);
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Delete a task by ID",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Task deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function destroy(Task $task)
    {
        $task->delete();

        // Dispatch job for task.deleted event
        // AblyPublishJob::dispatch('task.deleted', $task->toArray());
        AblyPublishJob::dispatch('task.deleted', [
            'id' => $task->id,
            'title' => $task->title,
        ]);

        return $this->showOne($task, "Task deleted Successfully", 204);
    }
}
