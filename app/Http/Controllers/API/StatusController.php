<?php

namespace App\Http\Controllers\API;

use App\Models\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use Throwable;

class StatusController extends Controller
{
    public $model = Status::class;

    public function index(PageRequest $request)
    {
        $status = Status::query();

        $data = app(Pipeline::class)->send($status)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 15));

        return responseSuccess(['status' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $validatedData = $request->validated();
            // Since validation passed, you can directly create the BRANCH.
            $status = Status::create($validatedData);
            return responseSuccess($status, 'Status has been successfully created');
        } catch (Throwable $e) {
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

        $status = Status::findOrFail($id);

        $request->validate([
            'key' => 'required',
            'value' => 'required',
        ]);

        $status->update($request->all());

        return responseSuccess($status, 'Status has been successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = Status::findOrFail($id);
        $status->delete();
        return responseSuccess([], 'status has been successfully deleted');
    }
}
