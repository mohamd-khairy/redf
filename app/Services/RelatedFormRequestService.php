<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Form;
use App\Models\Formable;
use App\Models\FormRequest;
use App\Filters\SortFilters;
use App\Models\FormRequestSide;
use App\Services\UploadService;
use App\Enums\FormRequestStatus;
use App\Models\FormPageItemFill;
use App\Models\FormAssignRequest;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Enums\FormAssignRequestType;
use App\Enums\StatusEnum;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use App\Models\FormRequestInformation;
use Illuminate\Support\Facades\Storage;

class RelatedFormRequestService
{
    public function storeRelatedFormFill($requestData)
    {
        return DB::transaction(function () use ($requestData) {
            $type = 'case';

            $formRequest = FormRequest::create([
                'form_id' => $requestData['id'],
                'branche_id' => $requestData['branche_id'] ?? null,
                'user_id' => Auth::id(),
                'status' => StatusEnum::active,
                'form_type' => 'related_case'
            ]);
            $formRequest->form_request_number = $requestData['case_number'] ?? rand(100000, 999999);
            $formRequest->name = $requestData['case_name'] ?? ($formRequest->form->name . "($formRequest->form_request_number)");
            $formRequest->save();
            // save related tables if get case_id
            if ($requestData->case_id) {
                // Create a new Formable record
                Formable::create([
                    'formable_id' => $requestData->case_id,
                    'form_request_id' => $formRequest->id,
                    'formable_type' => FormRequest::class,
                ]);

                $actionData = [
                    'form_request_id' => $requestData->case_id,
                    'formable_id' => $formRequest->id,
                    'formable_type' => FormRequest::class,
                    'msg' =>   $formRequest->form->name . ' تم اضافه ',
                ];
                saveFormRequestAction($actionData);
            }

            $this->processFormPages($requestData, $formRequest, $type);

            return $formRequest;
        });
    }

    public function updateRelatedFormFill($requestData, $id)
    {
        return DB::transaction(function () use ($requestData, $id) {
            $type = 'case';
            $formRequest = FormRequest::findOrFail($id);
            $formRequest->form_request_number = $requestData['case_number'] ?? $formRequest->form_request_number;
            $formRequest->name = $requestData['case_name'] ?? $formRequest->name;
            $formRequest->branche_id = $requestData['branche_id'] ?? $formRequest->branche_id ?? null;
            // dd($formRequest,$formRequest->branche_id , $requestData);

            $formRequest->save();

            // save related tables if get case_id
            if ($requestData->case_id) {
                // Update Formable record
                Formable::updateOrCreate([
                    'formable_id' => $requestData->case_id,
                    'form_request_id' => $formRequest->id,
                    'formable_type' => FormRequest::class,
                ]);
                $actionData = [
                    'form_request_id' => $requestData->case_id,
                    'formable_id' => $formRequest->id,
                    'formable_type' => FormRequest::class,
                    'msg' =>  $formRequest->name  . ' تم تحديث  ',
                ];
                saveFormRequestAction($actionData);
            }

            FormPageItemFill::where('form_request_id', $formRequest->id)->delete();

            $this->processFormPages($requestData, $formRequest, $type);

            return $formRequest;
        });
    }

    private function processFormPages($request, FormRequest $formRequest, $type = null)
    {
        $pagesInput = $request->input('pages', []);

        $pages = is_string($pagesInput) ? json_decode($pagesInput, true) : $pagesInput;
        $pageItems = collect($pages)->flatMap(fn ($page) => $page['items'] ?? []);

        $pageItems->each(function ($pageItem) use ($formRequest, $type) {
            $decodedValue = $pageItem['value'];

            if ($pageItem['type'] === 'file') {
                $file = $pageItem['value'];
                $decodedValue = $filePath = UploadService::store($file, 'formPages');
                // Create a new file record
                $fileRecord = new File([
                    'name' => 'form file',
                    'path' => $filePath,
                    'user_id' => auth()->id(),
                    'start_date' => now(),
                    'type' => $type ?? 'form',
                    'priority' => 'high',
                    'status' => 'active',
                ]);
                $fileRecord->fileable()->associate($formRequest); // Associate the file with the task
                $fileRecord->save();
            }

            FormPageItemFill::create([
                'value' => $decodedValue,
                'form_page_item_id' => $pageItem['form_page_item_id'],
                'user_id' => auth()->id(),
                'form_request_id' => $formRequest->id,
            ]);
        });
    }

    public function getFormRequest($request)
    {
        try {
            $query = FormRequest::with('form.pages.items', 'user', 'formAssignedRequests.assigner', 'form_page_item_fill.page_item', 'lastFormRequestInformation');

            if ($request->has('template_id')) {
                $query = $query->whereHas('form', fn ($q) => $q->where('template_id', $request->input('template_id')));
            }

            $data = app(Pipeline::class)->send($query)
                ->through([SortFilters::class])
                ->thenReturn();
            $pageSize = $request->input('pageSize', 15);
            $data = $pageSize == -1 ?  $data->get() : $data->paginate($pageSize);
            return $data;
        } catch (\Throwable $e) {
            // You could consider throwing an exception here if needed
            return null;
        }
    }

    public function assignRequest($requestData)
    {
        try {
            return DB::transaction(function () use ($requestData) {
                $formattedDate = Carbon::createFromFormat('Y-m-d', $requestData['date'])->toDateString();
                foreach ($requestData['form_request_id'] as $formRequestId) {
                    FormAssignRequest::where('form_request_id', $formRequestId)
                        ->where('status', 'active')
                        ->where('status', '!=', 'deleted')
                        ->update(['status' => 'deleted']);
                    $formUserId = Form::where('id', $formRequestId)->value('user_id');
                    $assignNew = FormAssignRequest::create([
                        'form_request_id' => $formRequestId,
                        'user_id' => $requestData['user_id'],
                        'date' => $formattedDate,
                        'assigner_id' => auth()->id(),
                        'status' => 'active',
                        'form_user_id' => $formUserId,
                        'type' => FormAssignRequestType::EMPLOYEE,
                    ]);
                    FormRequest::where('id', $formRequestId)->update(['status' => 'assigned']);
                    $actionData = [
                        'form_request_id' => $formRequestId,
                        'formable_id' => $assignNew->id,
                        'formable_type' => FormAssignRequest::class,
                        'msg' => 'تم اسناد القضيه ل موظف جديد',
                    ];

                    saveFormRequestAction($actionData);
                }
                return ['assignNew' => $assignNew];
            });
        } catch (\Throwable $e) {
            // You could consider throwing an exception here if needed
            return null;
        }
    }

    public function formRequestSide($request)
    {
        $validatedData = $request->validate([
            'form_request_id' => 'required|exists:form_requests,id',
            'claimant_id' => ['required', 'exists:users,id'],
            'defendant_id' => ['required', 'exists:users,id'],
        ]);
        $formRequestSide = FormRequestSide::create($validatedData);
        return responseSuccess($formRequestSide, 'Form Request Side has been successfully Created');
    }

    public function formRequestInformation($request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            // $validatedData['status'] = FormRequestStatus::PROCESSING;
            $courtFromRequest = $request->court;
            $latestCourtFromDatabase = FormRequestInformation::where('form_request_id', $request->form_request_id)
                ->latest()
                ->value('court');

            if ($latestCourtFromDatabase !== null && $courtFromRequest !== $latestCourtFromDatabase) {
                return responseFail('You cannot add the same court');
            }

            $formRequestInfo = FormRequestInformation::create($validatedData);
            // $formRequestInfo->form_request->status = FormRequestStatus::PROCESSING;
            $formRequestInfo->form_request->save();
            $calendarData = [
                'form_request_id' => $formRequestInfo->form_request_id,
                'calendarable_id' => $formRequestInfo->form_request_id,
                'calendarable_type' => FormRequest::class,
                'details' => $request->details ? $request->details : 'تم اضافه اجراء جديد',
                'user_id' => auth()->id(),
                'date' => $request->date,
            ];
            $calendar = saveCalendarFromRequest($calendarData);
            $actionData = [
                'form_request_id' => $formRequestInfo->form_request_id,
                'formable_id' => $formRequestInfo->id,
                'formable_type' => FormRequestInformation::class,
                'msg' => $request->details ? $request->details : 'تم اضافه اجراء جديد',
            ];
            $action = saveFormRequestAction($actionData);
            DB::commit();
            return responseSuccess($formRequestInfo, 'Form Request Information and Sessions have been successfully created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return responseFail($e->getMessage());
        }
    }
}
