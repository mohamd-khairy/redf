<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StageFormRequest;
use App\Models\Form;
use App\Models\Stage;
use App\Models\StageForm;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public $model = Stage::class;


    public function allStages(): \Illuminate\Http\JsonResponse
    {
        $data = Stage::all();
        return responseSuccess(['stages' => $data]);
    }

    public function storeFormStages(Request $request)
    {
        try {
//            $validatedData = $request->validated();
            $stageIds = $request->ids;
            $form = Form::find($request->form_id);
            $form->stages()->sync($stageIds);

            return responseSuccess([],'Stage Form has been successfully created');
        } catch (\Throwable $e) {
            // If validation fails, handle the validation errors here.
            return responseFail($e->getMessage());
        }
    }


    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function show(Stage $stage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function edit(Stage $stage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stage $stage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stage $stage)
    {
        //
    }
}
