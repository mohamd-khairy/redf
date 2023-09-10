<?php

namespace App\Http\Controllers\API;

use Throwable;
use App\Models\Form;
use App\Models\User;
use App\Models\FormRequest;
use Illuminate\Http\Request;
use App\Rules\StatusEnumRule;
use App\Models\FormRequestSide;
use App\Enums\FormRequestStatus;
use App\Http\Requests\FormAssign;
use App\Http\Requests\PageRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\FormRequestService;
use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormFillRequest;
use App\Http\Requests\InformationRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FormRequestResource;
use App\Models\File;
use Illuminate\Support\Facades\File as FacadesFile;

class FormRequestController extends Controller
{
    public $model = Form::class;
    private $formRequestService;

    public function __construct(FormRequestService $formRequestService)
    {
        // $this->middleware(['auth']);
        // $this->middleware('permission:list-form|edit-form|create-form|delete-form', ['only' => ['index', 'store']]);
        // $this->middleware('permission:create-form', ['only' => ['store']]);
        // $this->middleware('permission:edit-form', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete-form', ['only' => ['destroy', 'delete_all']]);
        $this->formRequestService = $formRequestService;
    }

    public function storeFormFill(FormFillRequest $request)
    {
        try {
            $requestData = $request->validated();
            $requestData['id'] = $request->id;
            $formRequest = $this->formRequestService->storeFormFill($request);
            return responseSuccess(['formRequest' => $formRequest], 'Form Fill has been successfully Created');
        } catch (\Throwable $th) {
            // throw $th;
            return responseFail($th->getMessage());
        }
    }

    public function updateFormFill(Request $request, $id)
    {
        try {
            $formRequest = $this->formRequestService->updateFormFill($request, $id);
            return responseSuccess($formRequest, 'Form Fill has been successfully updated');
        } catch (\Throwable $th) {
            return responseFail($th->getMessage());
        }
    }

    public function getFormRequest(PageRequest $request)
    {
        try {
            $data = $this->formRequestService->getFormRequest($request);
            if ($data) {
                return responseSuccess($data, 'Form requests retrieved successfully');
            } else {
                return responseFail('An error occurred while retrieving form requests');
            }
        } catch (\Throwable $th) {
            return responseFail($th->getMessage());
        }
    }

    public function getFormRequestfill($id)
    {
        try {
            $formfill = FormRequest::with(
                'form.pages.items',
                'user',
                'form_page_item_fill',
                'formRequestInformations',
                'formRequestSide',
                'lastFormRequestInformation',
                'request',
                'branche'
            )->find($id);

            return responseSuccess(new FormRequestResource($formfill), 'Form requests retrieved successfully');
        } catch (\Throwable $th) {
            return responseFail($th->getMessage());
        }
    }

    public function assignRequest(FormAssign $request)
    {
        try {
            $requestData = $request->validated();
            $requestData['assigner_id'] = auth()->user()->id;
            $result = $this->formRequestService->assignRequest($requestData);
            if ($result) {
                return responseSuccess($result);
            } else {
                return responseFail('An error occurred while assigning the form requests');
            }
        } catch (\Throwable $th) {
            return responseFail($th->getMessage());
        }
    }

    public function storeFormRequestSide(Request $request)
    {
        return $this->formRequestService->formRequestSide($request);
    }

    public function formRequestInformation(InformationRequest $request)
    {
        return $this->formRequestService->formRequestInformation($request);
    }

    public function deleteFormRequest($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $formRequest = FormRequest::findOrFail($id);
                // Delete related records
                $formRequest->formRequestInformations()->delete();
                $formRequest->formRequestSide()->delete();
                $formRequest->tasks()->delete();
                $formRequest->form_page_item_fill()->delete();
                // Delete the FormRequest itself
                $formRequest->delete();
                return responseSuccess('Form Request Deleted Successfully');
            });
        } catch (\Throwable $th) {
            return responseFail($th->getMessage());
        }
    }

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'form_request_id' => 'required|exists:form_requests,id',
            'status' => [new Enum(FormRequestStatus::class)],
        ]);

        if ($validator->fails()) {
            return responseFail($validator->errors()->first());
        }

        $response = FormRequest::where('id', $request->form_request_id)->update(['status' => $request->status]);

        return responseSuccess($response, 'Status updated successfully');
    }

    public function retrieveClaimant(Request $request)
    {
        $formRequestSide = FormRequestSide::select('claimant_id', 'defendant_id')->where('form_request_id', $request->form_request_id)->first();

        if ($formRequestSide) {

            $users = User::select('id', 'name')->whereIn('id', [$formRequestSide->claimant_id, $formRequestSide->defendant_id])->get();

            if ($users) {
                return responseSuccess($users, 'Retrieve Claimant And Defendant');
            }
        }

        return responseSuccess([], 'Retrieve Claimant And Defendant');
    }

    public function getFile($id)
    {
        $file = File::where(['fileable_id' => $id, 'fileable_type' => 'App\Models\FormRequest'])->first();

        if (!$file) {
            return responseFail('there is no file for this id');
        }

        return FacadesFile::get($file->file);
    }
}
