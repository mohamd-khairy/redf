<?php

namespace App\Http\Controllers\API;

use Throwable;
use Carbon\Carbon;
use App\Models\Form;
use App\Models\Formable;
use App\Models\FormPage;
use App\Models\FormRequest;
use Illuminate\Support\Str;
use App\Filters\SortFilters;
use App\Models\FormPageItem;
use Illuminate\Http\Request;
use App\Models\FormRequestSide;
use App\Services\UploadService;
use App\Enums\FormRequestStatus;
use App\Models\FormPageItemFill;
use App\Http\Requests\FormAssign;
use App\Models\FormAssignRequest;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\PageRequest;
use Illuminate\Support\Facades\DB;
use App\Enums\FormAssignRequestType;
use App\Http\Controllers\Controller;
use App\Http\Resources\FormResource;
use Illuminate\Support\Facades\Auth;
use App\Models\FormRequestInformation;
use App\Http\Requests\CreateFormRequest;
use App\Http\Requests\FormFillRequest;
use App\Http\Requests\FormUpdateRequest;
use App\Http\Resources\FormItemResource;
use App\Http\Requests\InformationRequest;
use App\Http\Resources\FormRequestResource;
use App\Services\FormService;

class FormsController extends Controller
{
    public $model = Form::class;
    private $formService;

    public function __construct(FormService $formService)
    {
        $this->middleware(['auth']);
        // $this->middleware('permission:list-form|edit-form|create-form|delete-form', ['only' => ['index', 'store']]);
        // $this->middleware('permission:create-form', ['only' => ['store']]);
        // $this->middleware('permission:edit-form', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete-form', ['only' => ['destroy', 'delete_all']]);
        $this->formService = $formService;
    }

    public function allForm(Request $request)
    {
        try {
            $forms = $this->formService->getAllForms();
            return responseSuccess($forms);
        } catch (\Throwable $th) {
            // throw $th;
            return responseFail($th->getMessage());
        }
    }

    public function createForm(CreateFormRequest $request)
    {
        try {
            $data = $request->all();
            $form = $this->formService->createForm($data);
            return responseSuccess($form);
        } catch (\Throwable $th) {
            // throw $th;
            return responseFail($th->getMessage());
        }
    }

    public function updateForm($id, FormUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $form = $this->formService->updateForm($id, $request);
            DB::commit();
            return responseSuccess(new FormItemResource($form));
        } catch (\Throwable $th) {
            Db::rollBack();
            // throw $th;
            return responseFail($th->getMessage());
        }
    }


    public function updateFormBasic($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $form = $this->formService->updateFormBasic($id, $request);
            DB::commit();
            return responseSuccess(new FormItemResource($form));
        } catch (\Throwable $th) {
            Db::rollBack();
            // throw $th;
            return responseFail($th->getMessage());
        }
    }

    public function deleteForm($id)
    {
        try {
            $form = Form::findOrFail($id);
            if (!$form) {
                return responseFail('Form not found');
            }
            // Delete the form
            $form->delete();
            return responseSuccess('Form deleted successfully');
        } catch (\Throwable $th) {
            return responseFail($th->getMessage());
        }
    }

    public function getFormsByTemplate(Request $request)
    {
        try {
            $template_id = $request->template_id;
            $allForms = Form::where('main', false)->when('template_id', function ($q) use ($template_id) {
                return $q->where('template_id', $template_id);
            })->get();
            return responseSuccess(FormResource::collection($allForms));
        } catch (\Throwable $th) {
            // throw $th;
            return responseFail($th->getMessage());
        }
    }

    public function listForm($id)
    {
        try {
            $form = Form::find($id);
            if (!$form) {
                return responseFail('there is no form with this id');
            }
            return responseSuccess(new FormItemResource($form));
        } catch (\Throwable $th) {
            // throw $th;
            return responseFail($th->getMessage());
        }
    }
}
