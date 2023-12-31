<?php

namespace App\Services;

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
use App\Enums\StatusEnum;
use App\Http\Requests\PageRequest;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use App\Models\FormRequestInformation;
use App\Models\Reminder;
use App\Models\StageForm;

class FormRequestService
{
    public function storeFormFill($requestData)
    {
        return DB::transaction(function () use ($requestData) {

            $number = $requestData['case_number'] ?? rand(100000, 999999);
            $formRequest = FormRequest::create([
                'form_id' => $requestData['id'],
                'branche_id' => $requestData['branche_id'] ?? null,
                'specialization_id' => $requestData['specialization_id'] ?? null,
                'organization_id' => $requestData['organization_id'] ?? null,
                'benefire_id' => $requestData['benefire_id'] ?? null,
                'case_type' => $requestData['case_type'] ?? null,
                'user_id' => Auth::id(),
                'status' => StatusEnum::PENDING,
                'form_type' => $requestData['type'],
                'case_date' => $requestData['case_date'],
                'department_id' => $requestData['department_id'],
                'form_request_number' => $number,
                'name' => $requestData['case_name'] ?? ($requestData['name'] . "($number)")
            ]);

            //handle file لائحه الدعوي
            if ($requestData->has('file')) {
                $formRequest->file = $this->processFormFile($requestData->file, $formRequest);
                $formRequest->save();
            }

            // save related tables if get case_id
            if ($requestData->case_id) {

                // Create a new Formable record
                Formable::firstOrCreate([
                    'formable_id' => $formRequest->id,
                    'form_request_id' => $requestData->case_id,
                    'formable_type' => FormRequest::class,
                ]);

                if ($requestData['type'] == 'related_case') {

                    $this->add_application($formRequest);

                    $this->remove_reminder((object)['form_request_id' => $formRequest->id]);

                } else {

                    /*********** add action ********* */
                    saveFormRequestAction(
                        form_request_id: $requestData->case_id,
                        formable_id: $formRequest->id,
                        formable_type: FormRequest::class,
                        msg: ' تم اضافه ' . $formRequest->name
                    );
                }
            }

            /*********** add Notifications ********* */
            sendMsgFormat(Auth::id(), $formRequest->name . ' تم اضافه ', ' تم إضافة  ( ' . $formRequest->name . ' ) ');

            /*********** add action ********* */
            saveFormRequestAction(
                form_request_id: $formRequest->id,
                formable_id: $formRequest->id,
                formable_type: FormRequest::class,
                msg: ' تم اضافه ' . $formRequest->name
            );

            $this->processFormPages($requestData, $formRequest);

            return $formRequest;
        });
    }

    public function updateFormFill($requestData, $id)
    {
        return DB::transaction(function () use ($requestData, $id) {

            $formRequest = FormRequest::findOrFail($id);
            $updatedData = [
                'branche_id' => $requestData['branche_id'],
                'specialization_id' => $requestData['specialization_id'],
                'organization_id' => $requestData['organization_id'],
                'benefire_id' => $requestData['benefire_id'],
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
                Formable::firstOrCreate(
                    [
                        'form_request_id' => $requestData->case_id,
                        'formable_id' => $formRequest->id,
                        'formable_type' => FormRequest::class,
                    ]
                );

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
        $pagesInput = $request->input('pages', null);
        if ($pagesInput) {
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
    }

    private function processFormFile($file, $formRequest)
    {
        if ($file) {
            $filePath = UploadService::store($file, 'formPages');
            // Create a new file record
            $fileRecord = new File([
                'name' => 'لائحه الدعوي',
                'path' => $filePath,
                'user_id' => auth()->id(),
                'start_date' => now(),
                'type' => $formRequest->form_type,
                'priority' => 'high',
                'status' => 'active',
            ]);
            $fileRecord->fileable()->associate($formRequest); // Associate the file with the task
            $fileRecord->save();

            return $filePath;
        }
        return null;
    }

    public function getFormRequest(PageRequest $request)
    {
        try {
            $query = FormRequest::with(
                'form.pages.items',
                'user',
                'benefire',
                'formAssignedRequests.assigner',
                'form_page_item_fill.page_item',
                'lastFormRequestInformation',
                'branch',
                'specialization',
                'request',
                'case',
                'lastFormRequestAction'
            );


            $query = $query->where('form_type', request('form_type', 'case'));

            if ($request->has('template_id')) {
                $query = $query->whereHas('form', fn ($q) => $q->where('template_id', $request->input('template_id')));
            }

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

                $request = FormRequest::where('id', $requestData['form_request_id'])->first();
                $request->formAssignedRequests()->delete();

                foreach ($requestData['user_id'] as $user_id) {
                    $items = [
                        'user_id' => $user_id,
                        'form_request_id' => $request->id,
                        'date' => date('Y-m-d'),
                        'assigner_id' => auth()->id(),
                        'status' => 'active',
                        'type' => FormAssignRequestType::EMPLOYEE,
                    ];

                    FormAssignRequest::create($items);

                    saveFormRequestAction(
                        form_request_id: $request->id,
                        formable_id: $user_id,
                        formable_type: FormAssignRequest::class,
                        msg: 'تم اسناد الطلب ل موظف ',
                    );
                }

                if ($request->form_type == 'case') {
                    $request->update(['status' => StatusEnum::ASSIGNED]);
                }

                if ($request->form_type == 'legal_advice') {
                    $request->update(['status' => StatusEnum::WAIT]);
                }

                if ($request->form_type == 'related_case') {
                    $request->update(['status' => StatusEnum::WAIT]);
                }

                return true;
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
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        return  $formRequestSide = FormRequestSide::updateOrCreate($request->only('form_request_id'), $validatedData);
    }

    public function formRequestInformation($request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->all();

            $formRequestInfo = FormRequestInformation::create($validatedData);

            $rstatus = $request->status;
            $status = $request->type == 'session' ? StatusEnum::WAIT_SESSION : ($rstatus ? CaseTypeEnum::$rstatus() : null);
            if ($status) {
                $formRequestInfo->form_request->update(['status' => $status]);
            }

            saveFormRequestAction(
                form_request_id: $formRequestInfo->form_request_id,
                formable_id: $formRequestInfo->id,
                formable_type: FormRequestInformation::class,
                msg: $request->type == 'session' ? 'تم اضافة جلسة ' : ($request->details ? $request->details : 'تم اضافه اجراء '),
            );

            if ($request->date || $request->sessionDate) {
                $calendarData = [
                    'form_request_id' => $formRequestInfo->form_request_id,
                    'calendarable_id' => $formRequestInfo->form_request_id,
                    'calendarable_type' => FormRequest::class,
                    'details' => $request->type == 'session' ? 'تم اضافة جلسة' : ($request->details ? $request->details : 'تم اضافه اجراء '),
                    'user_id' => auth()->id(),
                    'date' => $request->date ?? $request->sessionDate,
                    'type' => $request->type
                ];

                saveCalendarFromRequest($calendarData);
            }

            if ($formRequestInfo->date_of_receipt) {
                $this->add_reminder($formRequestInfo);
            } else {
                $this->remove_reminder($formRequestInfo);
            }

            DB::commit();

            return $formRequestInfo;
        } catch (\Exception $e) {

            DB::rollBack();
            return responseFail($e->getMessage());
        }
    }

    public function updateFormRequestInformation($id, $request)
    {
        try {
            DB::beginTransaction();

            $formRequestInfo = FormRequestInformation::find($id);
            $response = $formRequestInfo->update($request->only('date_of_receipt'));
            $formRequestInfo->refresh();
            if ($response && $formRequestInfo->date_of_receipt) {

                saveFormRequestAction(
                    form_request_id: $formRequestInfo->form_request_id,
                    formable_id: $formRequestInfo->id,
                    formable_type: FormRequestInformation::class,
                    msg: 'تم استلام الحكم',
                );

                if ($formRequestInfo->status != 'THIRD_RULE') {
                    $this->add_reminder($formRequestInfo);
                }
            }

            DB::commit();

            return $response;
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
        }
    }

    public function add_reminder($formRequestInfo)
    {
        $start_date = date('Y-m-d', strtotime($formRequestInfo->date_of_receipt));
        $end_date = date('Y-m-d', strtotime($formRequestInfo->date_of_receipt . "+ 30 day"));
        $color =  "#" . dechex(rand(0x000000, 0xFFFFFF));

        Reminder::updateOrCreate([
            'form_request_id' => $formRequestInfo->form_request_id,
            'form_request_information_id' => $formRequestInfo->id,
            'name' => "تم استلام الحكم",
        ], [
            'color' => $color,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        return true;
    }

    public function remove_reminder($formRequestInfo)
    {
        return Reminder::where([
            'form_request_id' => $formRequestInfo->form_request_id,
        ])->delete();
    }

    public function add_application($formRequest)
    {
        return Application::firstOrCreate([
            'form_request_id' => $formRequest->id,
            'form_id' => $formRequest->form_id
        ], [
            'stage_id' => StageForm::where('form_id', $formRequest->form_id)->orderBy('order', 'asc')->first()?->stage_id ?? 1
        ]);
    }
}
