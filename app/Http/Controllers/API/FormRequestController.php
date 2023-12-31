<?php

namespace App\Http\Controllers\API;

use App\Enums\FormEnum;
use App\Models\User;
use App\Models\FormRequest;
use Illuminate\Http\Request;
use App\Models\FormRequestSide;
use App\Enums\FormRequestStatus;
use App\Enums\StatusEnum;
use App\Http\Requests\FormAssign;
use App\Http\Requests\PageRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\FormRequestService;
use App\Http\Requests\FormFillRequest;
use App\Http\Requests\InformationRequest;
use App\Http\Requests\UpdateFormFillRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FormRequestResource;
use App\Models\File;

class FormRequestController extends Controller
{
    public $model = FormRequest::class;
    private $formRequestService;

    public function __construct(FormRequestService $formRequestService)
    {
        $this->middleware(['auth']);
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

    public function updateFormFill(UpdateFormFillRequest $request, $id)
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
                'case',
                'branche',
                'formAssignedRequests'
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
        $formRequestSide = $this->formRequestService->formRequestSide($request);

        return responseSuccess($formRequestSide, 'Form Request Side has been successfully Created');
    }

    public function formRequestInformation(InformationRequest $request)
    {
        $response = $this->formRequestService->formRequestInformation($request);

        return responseSuccess($response, 'Form Request Information and Sessions have been successfully created.');
    }

    public function updateFormRequestInformation($id, InformationRequest $request)
    {
        $response = $this->formRequestService->updateFormRequestInformation($id, $request);

        return responseSuccess($response, 'updated successfully');
    }

    public function deleteFormRequest($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $formRequest = FormRequest::with('requests')->findOrFail($id);
                // Delete related records
                $formRequest->calenders()->delete();
                $formRequest->formRequestActions()->delete();
                $formRequest->formRequestInformations()->delete();
                $formRequest->formRequestSide()->delete();
                $formRequest->tasks()->delete();
                $formRequest->applications()->delete();
                $formRequest->form_page_item_fill()->delete();
                if ($formRequest->type == 'case') {
                    FormRequest::whereIn('id', $formRequest->requests()->pluck('formable_id'))->delete();
                    $formRequest->requests()->delete();
                }
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
            'status' => ['required'],
        ]);

        if ($validator->fails()) {
            return responseFail($validator->errors()->first());
        }

        $formRequest = FormRequest::where('id', $request->form_request_id)->first();
        $response = $formRequest->update(['status' => request('status'), 'status_request' => request('status')]);

        /*********** update form request status عند الموافقه علي خطاب التنفيذ********* */
        $relatedCase = $this->updateStatus($formRequest->form->id); //form_id
        if (isset($formRequest->case->item) && isset($relatedCase->value)) {
            $formRequest->case->item->update(['status' => $relatedCase->value]);
        }
        /******************************************************************************* */

        $status = $request->status;
        saveFormRequestAction(
            form_request_id: $formRequest->case->item->id,
            formable_id: $formRequest->id,
            formable_type: FormRequest::class,
            msg: FormRequestStatus::$status() . " ( $formRequest->name ) "
        );

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

        $b64image = base64_encode(file_get_contents($file->file));

        return responseSuccess($b64image);
    }

    public function updateStatus($formId)
    {
        switch ($formId) {
                // case FormEnum::DEFENCE_CASE_FORM->value:
                //     $status = CaseTypeEnum::FIRST_RULE;
                //     break;

                // case FormEnum::CLAIM_CASE_FORM->value:
                //     $status = CaseTypeEnum::FIRST_RULE;
                //     break;

                // case FormEnum::RESUME_CASE_FORM->value:
                //     $status = CaseTypeEnum::SECOND_RULE;
                //     break;

                // case FormEnum::SOLICITATION_CASE_FORM->value:
                //     $status = CaseTypeEnum::THIRD_RULE;
                //     break;

            case FormEnum::IMPLEMENTATION_CASE_FORM->value:
                $status = StatusEnum::CLOSED;
                break;

            default:
                $status = null;
                break;
        }

        return $status;
    }
}
