<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;

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
    public function index(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'page' => 'nullable|numeric|min:1',
            'pageSize' => 'nullable|min:1',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }
        $page_size = request('pageSize', 15);
        $organizations = Organization::query();
        if (request('search')) {
            $organizations = $organizations->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%');
            });
        }
        if ($page_size == -1) {
            $organizations = $organizations->get();
        } else {
            $organizations = $organizations->paginate($page_size);
        }

        return responseSuccess(['organizations' => $organizations]);
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
        } catch (ValidationException $e) {
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
            'title' => 'required|string|max:255',
            'type' => 'required', // Enum values
            'user_id' => 'required|exists:users,id',
            'assigner_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'details' => 'nullable|string',
            'document_id' => 'required|exists:documents,id',
            'share_with' => 'nullable|string',
            'form_id' => 'nullable|exists:forms,id',
        ]);

        $task->update($request->all());

        return responseSuccess($task, 'task has been successfully Updated');
    }

    public function show($id)
    {
        $organization = Organization::findOrFail($id);
        return responseSuccess($organization, 'organization has been successfully showed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();
        return responseSuccess([], 'organization has been successfully deleted');
    }
}
