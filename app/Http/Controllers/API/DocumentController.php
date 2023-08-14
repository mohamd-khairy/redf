<?php

namespace App\Http\Controllers\api;

use App\Filters\SearchFilters;
use App\Filters\SortFilters;
use Carbon\Carbon;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Http\Requests\PageRequest;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

class DocumentController extends Controller
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
        $documents = Document::query();

        $data = app(Pipeline::class)->send($documents)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = $data->paginate($request->pageSize ?? 15);

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
            $validatedData['start_date'] = Carbon::createFromFormat('d-m-Y', $validatedData['start_date'])
                ->format('Y-m-d');
            $validatedData['end_date'] = Carbon::createFromFormat('d-m-Y', $validatedData['end_date'])
                ->format('Y-m-d');

            $document = Document::create($validatedData);
            return responseSuccess($document, 'document has been successfully created');
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

        $document = Document::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes', // Enum values
            'priority' => 'sometimes', // Enum values
            'user_id' => 'sometimes|exists:users,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'type' => 'sometimes',
        ]);

        $document->update($request->all());

        return responseSuccess($document, 'document has been successfully Updated');
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
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
        $document = Document::findOrFail($id);
        $document->delete();
        return responseSuccess([], 'Document has been successfully deleted');
    }
}
