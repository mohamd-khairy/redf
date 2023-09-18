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
}
