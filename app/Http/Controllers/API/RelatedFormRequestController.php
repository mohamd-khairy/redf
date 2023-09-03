<?php

namespace App\Http\Controllers\API;

use Throwable;
use App\Models\Form;
use App\Models\FormRequest;
use Illuminate\Http\Request;
use App\Http\Requests\FormAssign;
use App\Http\Requests\PageRequest;
use App\Http\Controllers\Controller;
use App\Services\RelatedFormRequestService;
use App\Http\Requests\FormFillRequest;
use App\Http\Requests\InformationRequest;
use App\Http\Resources\FormRequestResource;
use Illuminate\Support\Facades\DB;

class RelatedFormRequestController extends Controller
{
    public $model = Form::class;
    private $formRequestService;

    public function __construct(RelatedFormRequestService $formRequestService)
    {
        $this->middleware(['auth']);
        // $this->middleware('permission:list-form|edit-form|create-form|delete-form', ['only' => ['index', 'store']]);
        // $this->middleware('permission:create-form', ['only' => ['store']]);
        // $this->middleware('permission:edit-form', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete-form', ['only' => ['destroy', 'delete_all']]);
        $this->formRequestService = $formRequestService;
    }

    public function storeRelatedFormFill(Request $request)
    {
        try {
            $requestData = $request->all();
            $formRequest = $this->formRequestService->storeRelatedFormFill($request);
            return responseSuccess(['formRequest' => $formRequest], 'Form Fill has been successfully Created');
        } catch (\Throwable $th) {
            return responseFail($th->getMessage());
        }
    }

    public function updateRelatedFormFill(Request $request, $id)
    {
        try {
            $formRequest = $this->formRequestService->updateRelatedFormFill($request, $id);
            return responseSuccess([], 'Form Fill has been successfully updated');
        } catch (\Throwable $th) {
            return responseFail($th->getMessage());
        }
    }

    public function getRelatedFormRequest(PageRequest $request)
    {
        try {
            $data = $this->formRequestService->getRelatedFormRequest($request);
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
            $formfill = FormRequest::with('form.pages.items', 'user', 'form_page_item_fill', 'formRequestInformations', 'formRequestSide', 'lastFormRequestInformation', 'request')
                ->find($id);
            // dd($form)
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
}
