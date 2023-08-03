<?php

namespace App\Http\Controllers\Api;

use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganzationRequest;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
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
