<?php

namespace App\Http\Controllers\API;

use Throwable;
use App\Models\Form;
use App\Models\Formable;
use App\Models\FormRequest;
use Illuminate\Http\Request;
use App\Http\Requests\FormAssign;
use App\Http\Requests\PageRequest;
use App\Http\Controllers\Controller;
use App\Services\FormRequestService;
use App\Http\Requests\FormFillRequest;
use App\Http\Requests\InformationRequest;
use App\Http\Resources\FormRequestResource;

class FormRequestController extends Controller
{
    public $model = Form::class;
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
            return responseFail($th->getMessage());
        }
    }

    public function updateFormFill(Request $request, $id)
    {
        try {
            $formRequest = $this->formRequestService->updateFormFill($request, $id);
            return responseSuccess([], 'Form Fill has been successfully updated');
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
            return $this->handleException($th);
        }
    }

    public function getFormRequestfill($id)
    {
        try {
            $formfill = FormRequest::with('form.pages.items', 'user', 'form_page_item_fill', 'formRequestInformation', 'formRequestSide', 'lastFormRequestInformation')->find($id);
            // dd($form)
            return responseSuccess(new FormRequestResource($formfill), 'Form requests retrieved successfully');
        } catch (\Throwable $e) {
            // Return an error response if something goes wrong
            return responseFail($e->getMessage());
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
            return $this->handleException($th);
        }
    }

    public function updateAssignRequest(Request $request)
    {
        dd($request->all());
    }

    public function storeFormRequestSide(Request $request)
    {
        return $this->formRequestService->formRequestSide($request);
    }


    public function formRequestInformation(InformationRequest $request)
    {
        return $this->formRequestService->formRequestInformation($request);
    }

    public function FormAssignRequest(Request $request)
    {
        // Create a new Formable record
        Formable::create([
            'formable_id' => $request->formable_id,
            'form_request_id' => $request->form_request_id,
            'formable_type' => FormRequest::class,
        ]);
    }
    public function deleteFormRequest($id)
    {
    }
}
