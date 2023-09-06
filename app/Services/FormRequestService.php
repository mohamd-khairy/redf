<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\File;
use App\Enums\FormEnum;
use App\Models\Formable;
use App\Enums\CaseTypeEnum;
use App\Models\FormRequest;
use App\Filters\SortFilters;
use App\Models\FormRequestSide;
use App\Services\UploadService;
use App\Models\FormPageItemFill;
use App\Models\FormAssignRequest;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Enums\FormAssignRequestType;
use App\Enums\FormRequestStatus;
use App\Enums\StatusEnum;
use App\Http\Requests\PageRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\FormRequestInformation;
use App\Models\User;

class FormRequestService
{
    public function storeFormFill($requestData)
    {
        //  return $requestData;
        return DB::transaction(function () use ($requestData) {

            $number = $requestData['case_number'] ?? rand(100000, 999999);
            $formRequest = FormRequest::create([
                'form_id' => $requestData['id'],
                'branche_id' => $requestData['branche_id'],
                'specialization_id' => $requestData['specialization_id'],
                'category' => $requestData['category'],
                'case_type' => $requestData['case_type'],
                'user_id' => Auth::id(),
                'status' => StatusEnum::PENDING,
                'form_type' => $requestData['type'],
                'case_date' => $requestData['case_date'],
                'form_request_number' => $number,
                'name' => $requestData['case_name'] ?? ($requestData['name'] . "($number)")
            ]);
            // save related tables if get case_id
            if ($requestData->case_id) {

                // Create a new Formable record
                Formable::create([
                    'formable_id' => $requestData->case_id,
                    'form_request_id' => $formRequest->id,
                    'formable_type' => FormRequest::class,
                ]);

                if ($requestData['type'] == 'related_case') {
                    $formRequest->update(['status' => StatusEnum::WAIT]);
                    $relatedCase = $this->updateStatus($requestData['id']); //form_id
                    if (isset($formRequest->request->formable)) {
                        $formRequest->request->formable->update(['status' => $relatedCase->value]);
                    }

                    $assignNew = FormAssignRequest::create([
                        'form_request_id' => $formRequest->id,
                        'user_id' => Auth::id(),
                        'date' => date('Y-m-d'),
                        'assigner_id' => User::whereHas('role', function ($q) {
                            $q->where('id', 2);
                        })->first()->id,
                        'status' => 'active',
                        'type' => FormAssignRequestType::EMPLOYEE,
                    ]);
                }

                /*********** add action ********* */
                saveFormRequestAction(
                    form_request_id: $requestData->case_id,
                    formable_id: $formRequest->id,
                    formable_type: FormRequest::class,
                    msg: ' تم اضافه ' . $formRequest->form->name
                );
            }

            saveFormRequestAction(
                form_request_id: $formRequest->id,
                formable_id: $formRequest->id,
                formable_type: FormRequest::class,
                msg: ' تم اضافه ' . $formRequest->name
            );

            $this->processFormPages($requestData, $formRequest);

            return $formRequest;
        });

        // sendMsgFormat(Auth::id() , $formRequest->form->name . ' تم اضافه ' ,'اضافه قضيه');

    }

    public function updateStatus($formId)
    {
        switch ($formId) {
            case FormEnum::DEFENCE_CASE_FORM->value:
                $status = CaseTypeEnum::FIRST_RULE;
                break;

            case FormEnum::CLAIM_CASE_FORM->value:
                $status = CaseTypeEnum::FIRST_RULE;
                break;

            case FormEnum::RESUME_CASE_FORM->value:
                $status = CaseTypeEnum::SECOND_RULE;
                break;

            case FormEnum::SOLICITATION_CASE_FORM->value:
                $status = CaseTypeEnum::THIRD_RULE;
                break;

            default:
                $status = null;
                break;
        }

        return $status;
    }

    public function updateFormFill($requestData, $id)
    {
        return DB::transaction(function () use ($requestData, $id) {

            $formRequest = FormRequest::findOrFail($id);
            $updatedData = [
                'branche_id' => $requestData['branche_id'],
                'specialization_id' => $requestData['specialization_id'],
                'category' => $requestData['category'],
                'case_type' => $requestData['case_type'],
                'form_type' => $requestData['type'],
                'case_date' => $requestData['case_date'],
                'form_request_number' =>  $requestData['case_number'] ?? $formRequest->form_request_number,
                'name' => $requestData['case_name'] ?? $formRequest->name
            ];
            $formRequest->update($updatedData);

            // save related tables if get case_id
            if ($requestData->case_id) {
                // Update Formable record
                Formable::updateOrCreate([
                    'formable_id' => $requestData->case_id,
                    'form_request_id' => $formRequest->id,
                    'formable_type' => FormRequest::class,
                ]);

                saveFormRequestAction(
                    form_request_id: $requestData->case_id,
                    formable_id: $formRequest->id,
                    formable_type: FormRequest::class,
                    msg: ' تم تحديث   ' . $formRequest->name
                );
            }

            FormPageItemFill::where('form_request_id', $formRequest->id)->delete();

            $this->processFormPages($requestData, $formRequest);

            saveFormRequestAction(
                form_request_id: $formRequest->id,
                formable_id: $formRequest->id,
                formable_type: FormRequest::class,
                msg: ' تم تحديث   ' . $formRequest->name
            );

            return $formRequest;
        });
    }

    private function processFormPages($request, FormRequest $formRequest)
    {
        $pagesInput = $request->input('pages', []);

        $pages = is_string($pagesInput) ? json_decode($pagesInput, true) : $pagesInput;
        $pageItems = collect($pages)->flatMap(fn ($page) => $page['items'] ?? []);

        $pageItems->each(function ($pageItem) use ($formRequest) {
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
                    'type' => $formRequest->form_type,
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

    public function getFormRequest(PageRequest $request)
    {
        try {
            $query = FormRequest::with(
                'form.pages.items',
                'user',
                'formAssignedRequests.assigner',
                'form_page_item_fill.page_item',
                'lastFormRequestInformation',
                'branch',
                'specialization',
                'request'
            );

            if ($request->has('template_id')) {
                $query = $query->whereHas('form', fn ($q) => $q->where('template_id', $request->input('template_id')));
            }

            $query = $query->where('form_type', request('form_type', 'case'));


            $data = app(Pipeline::class)->send($query)->through([SortFilters::class])->thenReturn();

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

                    $assignNew = FormAssignRequest::create([
                        'form_request_id' => $formRequestId,
                        'user_id' => $requestData['user_id'],
                        'date' => $formattedDate,
                        'assigner_id' => auth()->id(),
                        'status' => 'active',
                        'type' => FormAssignRequestType::EMPLOYEE,
                    ]);

                    FormRequest::where('id', $formRequestId)->update(['status' => StatusEnum::ASSIGNED]);

                    saveFormRequestAction(
                        form_request_id: $formRequestId,
                        formable_id: $assignNew->id,
                        formable_type: FormAssignRequest::class,
                        msg: 'تم اسناد الطلب ل موظف جديد',
                    );
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

            $validatedData = $request->all();

            $formRequestInfo = FormRequestInformation::create($validatedData);

            if ($request->status) {
                $status = $request->status;
                $formRequestInfo->form_request->update(['status' => CaseTypeEnum::$status()]);
            }

            if ($request->date) {
                $calendarData = [
                    'form_request_id' => $formRequestInfo->form_request_id,
                    'calendarable_id' => $formRequestInfo->form_request_id,
                    'calendarable_type' => FormRequest::class,
                    'details' => $request->details ? $request->details : 'تم اضافه اجراء جديد',
                    'user_id' => auth()->id(),
                    'date' => $request->date,
                ];

                saveCalendarFromRequest($calendarData);
            }

            saveFormRequestAction(
                form_request_id: $formRequestInfo->form_request_id,
                formable_id: $formRequestInfo->id,
                formable_type: FormRequestInformation::class,
                msg: $request->details ? $request->details : 'تم اضافه اجراء جديد',
            );

            DB::commit();

            return responseSuccess($formRequestInfo, 'Form Request Information and Sessions have been successfully created.');
        } catch (\Exception $e) {

            DB::rollBack();
            return responseFail($e->getMessage());
        }
    }
}
