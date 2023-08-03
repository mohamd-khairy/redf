<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth']);
        // $this->middleware('permission:read-department', ['only' => ['index', 'show']]);
        // $this->middleware('permission:create-department', ['only' => ['store']]);
        // $this->middleware('permission:update-department', ['only' => ['update']]);
        // $this->middleware('permission:delete-department', ['only' => ['destroy']]);
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
        $departments = Department::query();
        if (request('search')) {
            $departments = $departments->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%');
            });
        }
        if ($page_size == -1) {
            $departments = $departments->get();
        } else {
            $departments = $departments->paginate($page_size);
        }

        return responseSuccess(['departments' => $departments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {

        try {
            $validatedData = $request->validated();
            // Since validation passed, you can directly create the department.
            $department = Department::create($validatedData);
            return responseSuccess($department, 'Department has been successfully created');
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

        $department = Department::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $department->update($request->all());

        return responseSuccess($department, 'Department has been successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = department::findOrFail($id);
        $department->delete();
        return responseSuccess([], 'department has been successfully deleted');
    }
}
