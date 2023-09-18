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
        $stages = Stage::query();

        if (request('form_id')) {
            $stages = $stages->whereHas('stage_forms', function ($q) {
                $q->where('form_id', request('form_id'));
            });
        }

        $data = $stages->get();


        return responseSuccess(['stages' => $data]);
    }

    public function storeFormStages(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'stages' => 'required|array',
                'form_id' => 'required|exists:forms,id',
            ]);

            if ($validate->fails()) {
                return responseFail($validate->messages()->first());
            }

            $collectStages = collect($request->stages);
            $stage_forms = $collectStages->mapWithKeys(function($item) {
                return [$item['stage_id'] => ['order'=>$item['order']] ];
            });

            $form = Form::find($request->form_id);
            $form->stages()->sync($stage_forms);

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