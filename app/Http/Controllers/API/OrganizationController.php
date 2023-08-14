<?php

namespace App\Http\Controllers\Api;

use App\Filters\SearchFilters;
use App\Filters\SortFilters;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganzationRequest;
use App\Http\Requests\PageRequest;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrganizationController extends Controller
{
    public $model = Organization::class;

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
        $organizations = Organization::query();

        $data = app(Pipeline::class)->send($organizations)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize',15));

        return responseSuccess(['organizations' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganzationRequest $request)
    {

        try {
            $validatedData = $request->validated();
            // Since validation passed, you can directly create the organization.
            $organization = Organization::create($validatedData);
            return responseSuccess($organization, 'Organization has been successfully created');
        } catch (ValidationException $e) {
            // If validation fails, handle the validation errors here.
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

        $organization = Organization::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $organization->update($request->all());

        return responseSuccess($organization, 'organization has been successfully Updated');
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
