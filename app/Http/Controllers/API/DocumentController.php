<?php

namespace App\Http\Controllers\api;

use App\Filters\SearchFilters;
use App\Filters\SortFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Http\Requests\PageRequest;
use App\Models\File;
use App\Services\UploadService;
use Illuminate\Pipeline\Pipeline;
use Throwable;

class DocumentController extends Controller
{
    public $model = File::class;

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
        $documents = File::query();

        if (request('type')) {
            $documents = $documents->where('type', request('type'));
        }

        $data = app(Pipeline::class)->send($documents)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 15));

        return responseSuccess(['documents' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $start_date = Carbon::createFromFormat('d-m-Y', $validatedData['start_date'])->format('Y-m-d');
            $end_date = Carbon::createFromFormat('d-m-Y', $validatedData['end_date'])->format('Y-m-d');

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = $file->getClientOriginalName();
                $filePath = UploadService::store($file, 'files');
            }

            $fileRecord = new File([
                'name' => $filename ?? null,
                'path' => $filePath ?? null,
                'user_id' => auth()->id(),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'type' => 'file',
                'priority' => 'high',
                'status' => $request->status ?? null
            ]);
            $fileRecord->fileable()->associate([]); // Associate the file with the task
            $fileRecord->save();

            return responseSuccess($fileRecord, 'document has been successfully created');
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
        $document = File::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes', // Enum values
            'priority' => 'sometimes', // Enum values
            'user_id' => 'sometimes|exists:users,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'type' => 'sometimes',
        ]);

        $data = $request->except('file');

        $data['start_date'] = $request->start_date ?  Carbon::createFromFormat('d-m-Y', $data['start_date'])->format('Y-m-d') : $document->start_date;
        $data['end_date'] = $request->end_date ?  Carbon::createFromFormat('d-m-Y', $data['end_date'])->format('Y-m-d') : $document->end_date;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['name'] = $file->getClientOriginalName();
            $data['path'] = UploadService::store($file, 'files');
        }

        $document->update($data);

        return responseSuccess($document, 'document has been successfully Updated');
    }

    public function show($id)
    {
        $document = File::findOrFail($id);
        return responseSuccess($document, 'Document has been successfully showed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document = File::findOrFail($id);
        $document->delete();
        return responseSuccess([], 'Document has been successfully deleted');
    }
}
