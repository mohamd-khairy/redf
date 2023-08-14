<?php

namespace App\Http\Controllers\Api;

use App\Filters\SearchFilters;
use App\Filters\SortFilters;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Http\Requests\PageRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pipeline\Pipeline;
use Throwable;

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
    public function index(PageRequest $request)
    {
        $departments = Department::query();

        $data = app(Pipeline::class)->send($departments)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = $data->paginate($request->pageSize ?? 15);

        return responseSuccess(['departments' => $data]);
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
        } catch (Throwable $e) {
            // If validation fails, handle the validation errors here.
            return responseFail($e->getMessage());
        }
    }

    public function show($id)
    {
        $department = Department::findOrFail($id);
        return responseSuccess($department, 'department has been successfully showed');
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
