<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = TaskResource::collection(
            Task::where('user_id', Auth::id())->get()
        );
        return $this->successResponse($data, 'Yes', 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);
        return $this->successResponse(
            new TaskResource($task)
            , 'Success Inserting', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if ($task->user_id != Auth::id())
            return $this->errorResponse('', 'Not Authored', 403);

        return $this->successResponse(
            new TaskResource($task),
            'Success Showing',
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if ($task->user_id != Auth::id())
            return $this->errorResponse('', 'Not Authored', 403);

        if ($request->has('name')) $task->name = $request->name;
        if ($request->has('description')) $task->description = $request->ndescriptioname;
        if ($request->has('priority')) $task->priority = $request->priority;

        if ($task->isClean())
            return $this->errorResponse(
                new TaskResource($task), 'Filed Updating please change any value', 400
            );
        $task->save();
        return $this->successResponse(
            new TaskResource($task), 'Success  Updating', 200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id != Auth::id())
            return $this->errorResponse('', 'Not Authored', 403);

        $data = new TaskResource($task);
        $task->delete();
        return $this->successResponse(
            $data,
            'Success Deleting',
            200
        );
    }

    public function isNotAuth($user_id)
    {
        if ($user_id != Auth::id())
            return $this->errorResponse('', 'Not Authored', 403);
    }
}
