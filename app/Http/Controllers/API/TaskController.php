<?php

namespace App\Http\Controllers\API;

use DateTime;
use Throwable;
use Carbon\Carbon;
use App\Models\File;
use App\Models\Task;
use App\Models\FormRequest;
use App\Filters\SortFilters;
use Illuminate\Http\Request;
use App\Filters\SearchFilters;
use App\Services\UploadService;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\PageRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
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
        try {

            $this->updateTaskStage();

            $tasks = Task::with('user:id,name,avatar', 'assigner:id,name,avatar', 'file');

            $data = app(Pipeline::class)->send($tasks)->through([
                SearchFilters::class,
                SortFilters::class,
            ])->thenReturn();

            $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 200));

            $data = $data->groupBy('stage_id');

            $data =  collect(Task::$stages)->map(function ($stage) use ($data) {
                $stage['tasks'] = isset($data[$stage['id']]) ? $data[$stage['id']] : [];
                return $stage;
            });

            return responseSuccess(['tasks' => $data]);

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request->all();
        try {
            // return $request->all();
            $validatedData = $request->validated();
            return $validatedData['files'];

            unset($validatedData['files']);
            // Parse the due_date
            $validatedData['due_date'] = Carbon::createFromFormat('d-m-Y', $validatedData['due_date'])->format('Y-m-d');
            // Create the task
            $task = Task::create($validatedData);
            // Handle the file upload

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $uploadedFile) {
                    dd($uploadedFile);
                    $filename = $uploadedFile->getClientOriginalName();
                    $filePath = UploadService::store($uploadedFile, 'tasks');
                    // Create a new file record for each uploaded file
                    $fileRecord = new File([
                        'name' => $filename,
                        'path' => $filePath,
                        'user_id' => auth()->id(),
                        'start_date' => now(),
                        'type' => 'task',
                        'priority' => 'high',
                    ]);
                    $fileRecord->fileable()->associate($task);
                    $fileRecord->save();
                }
            }

            if ($request->form_request_id) {
                saveFormRequestAction(
                    form_request_id: $request->form_request_id,
                    formable_id: $request->form_request_id,
                    formable_type: FormRequest::class,
                    msg: 'تم اسناد المهمه الي قضيه '
                );
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
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes', // Enum values
            'user_id' => 'sometimes|exists:users,id',
            'assigner_id' => 'sometimes|exists:users,id',
            'department_id' => 'sometimes|exists:departments,id',
            'due_date' => 'sometimes|date',
            'details' => 'sometimes|string',
            'share_with' => 'sometimes|string',
            'form_request_id' => 'sometimes|exists:forms,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx',
            'stage_id' => 'nullable|integer',
        ]);

        unset($validatedData['file']);

        $task = Task::with('user:id,name', 'assigner:id,name', 'file')->findOrFail($id);

        $task->update($validatedData);

        // Handle the file update if a new file is provided
        if ($request->hasFile('file')) {

            // Delete the old file records
            $file = $task->file;
            Storage::delete($file->path); // Delete the file from storage
            $file->delete(); // Delete the file record from the database

            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $filePath = UploadService::store($file, 'tasks');

            // Create a new file record
            $fileRecord = new File([
                'name' => $filename,
                'path' => $filePath,
                'user_id' => auth()->id(),
                'start_date' => now(),
                'type' => 'task',
                'priority' => 'high'
            ]);

            // Associate the new file with the updated task
            $fileRecord->fileable()->associate($task);
            $fileRecord->save();
        }

        return responseSuccess($task, 'task has been successfully Updated');
    }

    public function show($id)
    {
        $task = Task::with('user:id,name', 'assigner:id,name', 'file')->findOrFail($id);

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

    public function updateTaskStage()
    {
        // get todate date
        $ldate = new DateTime('today');

        $tasks = Task::whereDate('due_date', '<', $ldate)
            ->where(function ($query) {
                $query->where('stage_id', 1)
                    ->orWhere('stage_id', 2);
            })
            ->update(['stage_id' => 3]);

        return responseSuccess([], 'Task has been successfully updated');
    }
}
