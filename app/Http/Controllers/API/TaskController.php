<?php

namespace App\Http\Controllers\Api;

use App\Filters\SearchFilters;
use App\Filters\SortFilters;
use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use Illuminate\Pipeline\Pipeline;
use Throwable;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        // $this->middleware('permission:read-organization', ['only' => ['index', 'show']]);
        // $this->middleware('permission:create-organization', ['only' => ['store']]);
        // $this->middleware('permission:update-organization', ['only' => ['update']]);
        // $this->middleware('permission:delete-organization', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PageRequest $request)
    {
        $tasks = Task::query();

        $data = app(Pipeline::class)->send($tasks)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = $data->paginate(request('pageSize', 15));

        return responseSuccess(['tasks' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {

        try {
            $validatedData = $request->validated();
            $validatedData['due_date'] = Carbon::createFromFormat('d-m-Y', $validatedData['due_date'])
                ->format('Y-m-d');
            $task = Task::create($validatedData);
            return responseSuccess($task, 'Task has been successfully created');
        } catch (Throwable $e) {
            return responseFail($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $task = Task::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes', // Enum values
            'user_id' => 'sometimes|exists:users,id',
            'assigner_id' => 'sometimes|exists:users,id',
            'due_date' => 'sometimes|date',
            'details' => 'sometimes|string',
            'document_id' => 'sometimes|exists:documents,id',
            'share_with' => 'sometimes|string',
            'form_id' => 'sometimes|exists:forms,id',
        ]);

        $task->update($request->all());

        return responseSuccess($task, 'task has been successfully Updated');
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return responseSuccess($task, 'task has been successfully showed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return responseSuccess([], 'Task has been successfully deleted');
    }
}