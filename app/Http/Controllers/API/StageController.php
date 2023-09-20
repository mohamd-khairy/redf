<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StageFormRequest;
use App\Http\Resources\StageFormResource;
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
        $validate = Validator::make(request()->all(), [
            'form_id' => 'required|exists:forms,id',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = StageForm::with('stage')->where('form_id', request('form_id'))->get();

        return responseSuccess(['stages' => StageFormResource::collection($data)]);
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
            $stage_forms = $collectStages->mapWithKeys(function ($item) {
                return [$item['stage_id'] => ['order' => $item['order']]];
            });

            $form = Form::find($request->form_id);
            $form->stages()->sync($stage_forms);

            return responseSuccess([], 'Stage Form has been successfully created');
        } catch (\Throwable $e) {
            return responseFail($e->getMessage());
        }
    }
}
