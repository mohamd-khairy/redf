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
use Illuminate\Support\Facades\Auth;
use App\Models\FormRequestInformation;

class FormRequestService
{
    public function storeFormFill($requestData)
    {
        return DB::transaction(function () use ($requestData) {
            $formRequest = FormRequest::create([
                'form_id' => $requestData['id'],
                'user_id' => Auth::id(),
                'status' => FormRequestStatus::PENDING,
            ]);
            $formRequest->form_request_number = $requestData['case_number'] ?? rand(100000, 999999);
            $formRequest->name = $requestData['case_name'] ?? ($formRequest->form->name . "($formRequest->case_number)");
            $formRequest->save();


            // save related tables if get case_id
            if($requestData->case_id){
                // Create a new Formable record
                Formable::create([
                    'formable_id' => $requestData->case_id,
                    'form_request_id' => $formRequest->id,
                    'formable_type' => FormRequest::class,
                ]);
            }
            $this->processFormPages($requestData, $formRequest);

            $actionData = [
                'form_request_id' => $formRequest->id,
                'formable_id' => $formRequest->id,
                'formable_type' => FormRequest::class,
                'msg' => 'تم اضافه قضيه جديده',
            ];

            saveFormRequestAction($actionData);

            return $formRequest;
        });
    }

    public function updateFormFill($requestData, $id)
    {

        return DB::transaction(function () use ($requestData, $id) {

            $formRequest = FormRequest::findOrFail($id);

            $formRequest->form_request_number = $requestData['case_number'] ?? $formRequest->form_request_number;
            $formRequest->name = $requestData['case_name'] ?? $formRequest->name;
            $formRequest->save();

            // save related tables if get case_id
            if($requestData->case_id){
                // Update Formable record
                Formable::updateOrCreate([
                    'formable_id' => $requestData->case_id,
                    'form_request_id' => $formRequest->id,
                    'formable_type' => FormRequest::class,
                ]);
            }
            FormPageItemFill::where('form_request_id', $formRequest->id)->delete();
            $this->processFormPages($requestData, $formRequest);

            $actionData = [
                'form_request_id' => $formRequest->id,
                'formable_id' => $formRequest->id,
                'formable_type' => FormRequest::class,
                'msg' => 'تم تحديث القضيه ',
            ];

            saveFormRequestAction($actionData);

            return $formRequest;
        });
    }

    private function processFormPages($request, FormRequest $formRequest)
    {
        $pagesInput = $request->input('pages', []);

        $pages = is_string($pagesInput) ? json_decode($pagesInput, true) : $pagesInput;
        $pageItems = collect($pages)->flatMap(fn ($page) => $page['items'] ?? []);

        $pageItems->each(function ($pageItem) use ($formRequest) {
            $decodedValue = $pageItem['type'] === 'file' ? UploadService::store($pageItem['value'], 'formPages') : $pageItem['value'];

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
            $validatedData['status'] = FormRequestStatus::PROCESSING;
            $formRequestInfo = FormRequestInformation::create($validatedData);
            $formRequestInfo->form_request->status = FormRequestStatus::PROCESSING;
            $formRequestInfo->form_request->save();
            $calendarData = [
                'form_request_id' => $formRequestInfo->form_request_id,
                'calendarable_id' => $formRequestInfo->form_request_id,
                'calendarable_type' => FormRequest::class,
                'details' => $request->details ? $request->details : 'تم اضافه اجراء جديد' ,
                'user_id' => auth()->id(),
                'date' => $request->date,
            ];
             $calendar = saveCalendarFromRequest($calendarData);
            $actionData = [
                'form_request_id' => $formRequestInfo->form_request_id,
                'formable_id' => $formRequestInfo->id,
                'formable_type' => FormRequestInformation::class,
                'msg' => $request->details ? $request->details : 'تم اضافه اجراء جديد' ,
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

?>
