<?php

namespace App\Http\Controllers\Api;

use App\Models\Branch;
use App\Filters\SortFilters;
use Illuminate\Http\Request;
use App\Filters\SearchFilters;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\PageRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrancheRequest;
use Throwable;

class BranchController extends Controller
{
    public $model = Branch::class;

    public function index(PageRequest $request)
    {
        $branches = Branch::query();

        $data = app(Pipeline::class)->send($branches)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = $data->paginate(request('pageSize', 15));

        return responseSuccess(['branches' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrancheRequest $request)
    {

        try {
            $validatedData = $request->validated();
            // Since validation passed, you can directly create the BRANCH.
            $branch = Branch::create($validatedData);
            return responseSuccess($branch, 'Branch has been successfully created');
        } catch (Throwable $e) {
            // If validation fails, handle the validation errors here.
            return responseFail($e->getMessage());
        }
    }


    public function show($id)
    {
        $branches = Branch::findOrFail($id);
        return responseSuccess($branches, 'branches has been successfully showed');
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

        $branch = Branch::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $branch->update($request->all());

        return responseSuccess($branch, 'Branch has been successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        return responseSuccess([], 'branch has been successfully deleted');
    }
}
