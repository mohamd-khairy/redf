<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Carbon\Carbon;
use App\Models\File;
use App\Models\Task;
use App\Filters\SortFilters;
use Illuminate\Http\Request;
use App\Filters\SearchFilters;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\PageRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use App\Services\UploadService;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public $model = Task::class;

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
        $tasks = Task::with('user:id,name', 'assigner:id,name', 'file');

        $data = app(Pipeline::class)->send($tasks)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 15));

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
            unset($validatedData['file']);
            // Parse the due_date
            $validatedData['due_date'] = Carbon::createFromFormat('d-m-Y', $validatedData['due_date'])
                ->format('Y-m-d');
            // Create the task
            $task = Task::create($validatedData);
            // Handle the file upload
            if ($request->hasFile('file')) {

                $file = $request->file('file');
                $filename = $file->getClientOriginalName();
                $filePath = UploadService::store($file, 'tasks');

                // Create a new file record
                $fileRecord = new File([
                    'name' => $filename,
                    'path' => $filePath,
                ]);
                $fileRecord->fileable()->associate($task); // Associate the file with the task
                $fileRecord->save();
            }

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
        $existingFiles = $task->files;
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes', // Enum values
            'user_id' => 'sometimes|exists:users,id',
            'assigner_id' => 'sometimes|exists:users,id',
            'due_date' => 'sometimes|date',
            'details' => 'sometimes|string',
            'share_with' => 'sometimes|string',
            'form_request_id' => 'sometimes|exists:forms,id',
            'file' => 'sometimes|file|mimes:jpg,jpeg,png,pdf,docx',
        ]);

        unset($validatedData['file']);

        // Delete the old file records
        foreach ($existingFiles as $file) {
            Storage::delete($file->path); // Delete the file from storage
            $file->delete(); // Delete the file record from the database
        }
        $task->update($request->all());

        // Handle the file update if a new file is provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $filePath = UploadService::store($file, 'tasks');

            // Create a new file record
            $fileRecord = new File([
                'name' => $filename,
                'path' => $filePath,
            ]);

            // Associate the new file with the updated task
            $fileRecord->fileable()->associate($task);
            $fileRecord->save();
        }

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
