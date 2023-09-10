<?php

namespace App\Http\Controllers\Api;

use App\Filters\SortFilters;
use Illuminate\Http\Request;
use App\Filters\SearchFilters;
use App\Models\Specialization;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\PageRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\SpecializeRequest;
use Throwable;

class SpecializationController extends Controller
{
    public $model = Specialization::class;

    public function index(PageRequest $request)
    {
        $specializations = Specialization::query();

        $data = app(Pipeline::class)->send($specializations)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 15));

        return responseSuccess(['specializations' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SpecializeRequest $request)
    {

        try {
            $validatedData = $request->validated();
            // Since validation passed, you can directly create the BRANCH.
            $branch = Specialization::create($validatedData);
            return responseSuccess($branch, 'Specialization has been successfully created');
        } catch (Throwable $e) {
            // If validation fails, handle the validation errors here.
            return responseFail($e->getMessage());
        }
    }


    public function show($id)
    {
        $specializations = Specialization::findOrFail($id);
        return responseSuccess($specializations, 'Specialization has been successfully showed');
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

        $specialization = Specialization::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

        $specialization->update($request->all());

        return responseSuccess($specialization, 'Specialization has been successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $specialization = Specialization::findOrFail($id);
        $specialization->delete();
        return responseSuccess([], 'Specialization has been successfully deleted');
    }
}
