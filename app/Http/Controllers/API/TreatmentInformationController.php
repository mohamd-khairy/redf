<?php

namespace App\Http\Controllers\Api;

use App\Filters\SortFilters;
use Illuminate\Http\Request;
use App\Filters\SearchFilters;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\PageRequest;
use App\Http\Controllers\Controller;
use App\Models\TreatmentInformation;
use App\Http\Requests\TreatmentInformationRequest;

class TreatmentInformationController extends Controller
{
    public $model = TreatmentInformation::class;

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
        $treatmentInformations = TreatmentInformation::query();

        $data = app(Pipeline::class)->send($treatmentInformations)->through([
        ])->thenReturn();

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 15));

        return responseSuccess(['treatmentInformations' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TreatmentInformationRequest $request)
    {
        try {

            $validatedData = $request->validated();

            // Create the treatment information record
            $treatmentInformation = TreatmentInformation::create($validatedData);


            return responseSuccess($treatmentInformation, 'Treatment information has been successfully created');
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
    public function update(TreatmentInformationRequest  $request, $id)
    {
        try {
            // Find the treatment by ID
            $treatmentInformation = TreatmentInformation::findOrFail($id);

            $validatedData = $request->validated();

            // Update the treatment information
            $treatmentInformation->update($validatedData);

            return responseSuccess($treatmentInformation, 'Treatment information has been successfully updated');
        } catch (Throwable $e) {
            return responseFail($e->getMessage());
        }
    }

    public function show($id)
    {
        $treatmentInformation = TreatmentInformation::with('treatment:id,name')->findOrFail($id);

        return responseSuccess($treatmentInformation, 'Treatment information has been successfully showed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $treatmentInformation = TreatmentInformation::findOrFail($id);
        $treatmentInformation->delete();

        return responseSuccess([], 'Treatment information has been successfully deleted');
    }
}
