<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StageFormRequest;
use App\Models\Form;
use App\Models\Stage;
use App\Models\StageForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            $validate = Validator::make($request->all(), [
                'stage_ids' => 'required|array',
                'stage_ids.*' => 'required|exists:stages,id',
                'form_id' => 'required|exists:forms,id',
            ]);

            if ($validate->fails()) {
                return responseFail($validate->messages()->first());
            }

            $form = Form::find($request->form_id);
            $form->stages()->sync($request->stage_ids);

            return responseSuccess([], 'Stage Form has been successfully created');
        } catch (\Throwable $e) {
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
