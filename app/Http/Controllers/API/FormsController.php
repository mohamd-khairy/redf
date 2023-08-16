<?php

namespace App\Http\Controllers\API;

use Throwable;
use Carbon\Carbon;
use App\Models\Form;
use App\Models\FormPage;
use App\Models\FormRequest;
use Illuminate\Support\Str;
use App\Filters\SortFilters;
use App\Models\FormPageItem;
use Illuminate\Http\Request;
use App\Models\AssignRequest;
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
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateFormRequest;
use App\Http\Requests\FormUpdateRequest;
use App\Http\Resources\FormItemResource;
use App\Services\UploadService;

class FormsController extends Controller
{
    public $model = Form::class;

    public function __construct()
    {
        $this->middleware(['auth']);
        // $this->middleware('permission:list-form|edit-form|create-form|delete-form', ['only' => ['index', 'store']]);
        // $this->middleware('permission:create-form', ['only' => ['store']]);
        // $this->middleware('permission:edit-form', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete-form', ['only' => ['destroy', 'delete_all']]);
    }

    public function allForm(Request $request)
    {
        try {
            $forms = Form::with('template')->paginate(15);
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
            $data['user_id'] = auth()->user()->id;
            $form = Form::firstOrCreate($data);
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
            $form = Form::find($id);

            if (!$form) {
                return responseFail('there is no form with this id');
            }
            $data = $this->update($request, $form);
            DB::commit();
            return responseSuccess(new FormItemResource($form));
        } catch (\Throwable $th) {
            Db::rollBack();
            // throw $th;
            return responseFail($th->getMessage());
        }
    }

    public function update($request, $form)
    {
        $form->update($request->all() + ['user_id' => Auth::id()]);
        // Delete old form pages and their items
        $form->pages()->each(function ($page) {
            $page->items()->delete();
            $page->delete();
        });
        // Create new form pages with new elements
        $pagesData = $request->input('pages');
        foreach ($pagesData as $pageData) {

            $page = new FormPage(['title' => $pageData['title']['title'], 'editable' =>  $pageData['title']['editing'] == false ? 0 : 1]);
            $form->pages()->save($page);

            if (isset($pageData['items']) && is_array($pageData['items'])) {
                foreach ($pageData['items'] as $itemData) {
                    if (!isset($itemData['removed']) || !$itemData['removed']) {
                        $item = new FormPageItem(collect($itemData)->only(['type', 'label', 'notes', 'width', 'height', 'enabled', 'required', 'website_view', 'childList'])->toArray());
                        $page->items()->save($item);
                    }
                }
            }
        }
        return $form->refresh();
    }

    public function updateFormBasic($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $form = Form::find($id);
            if (!$form) {
                return responseFail('there is no form with this id');
            }
            $data = $form->update($request->only('name', 'description'));
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
                return response()->json(['message' => 'Form not found'], 404);
            }
            // Delete the form
            $form->delete();
            return response()->json(['message' => 'Form deleted successfully']);
        } catch (\Throwable $th) {
            return responseFail($th->getMessage());
        }
    }

    public function getFormsByTemplate(Request $request)
    {
        try {
            $template_id = $request->template_id;

            $allForms = Form::when('template_id', function ($q) use ($template_id) {
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

    public function storeFormFill(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $formRequest = FormRequest::create([
                    'form_id' => $request->id,
                    'user_id' => auth()->id(),
                    'status' => FormRequestStatus::PENDING,
                ]);
                $this->processFormPages($request, $formRequest);
                $calendarData = [
                    'calendarable_id'=>$formRequest->id,
                    'calendarable_type'=>FormRequest::class,
                    'user_id' => auth()->id(),
                    'date'=>now(),
                ];
                $calendar = saveCalendarFromRequest($calendarData);
                $actionData = [
                    'formable_id'=>$formRequest->id,
                    'formable_type'=>FormRequest::class,
                    'msg'=>'تم اضافه قضيه جديده',
                ];
                $calendar = saveFormRequestAction($actionData);
                return responseSuccess([], 'Form Fill has been successfully Created');
            });
        } catch (\Throwable $th) {
            return responseFail($th->getMessage());
        }
    }
    public function updateFormFill(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {

                $formRequest = FormRequest::findOrFail($id);

                // Delete existing form page item fills for this form request
                FormPageItemFill::where('form_request_id', $formRequest->id)->delete();

                $this->processFormPages($request, $formRequest);
                $calendarData = [
                    'calendarable_id'=>$formRequest->id,
                    'calendarable_type'=>FormRequest::class,
                    'user_id' => auth()->id(),
                    'date'=>now(),
                ];
                $calendar = saveCalendarFromRequest($calendarData);
                $actionData = [
                    'formable_id'=>$formRequest->id,
                    'formable_type'=>FormRequest::class,
                    'msg'=>'تم تحديث القضيه ',
                ];
                $calendar = saveFormRequestAction($actionData);
                return responseSuccess([], 'Form Fill has been successfully updated');
            });
        } catch (\Throwable $th) {
            return responseFail($th->getMessage());
        }
    }
    private function processFormPages(Request $request, FormRequest $formRequest)
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
    public function getFormRequest(PageRequest $request)
    {
        try {
            $query = FormRequest::with('form.pages.items', 'user', 'formAssignedRequests.assigner', 'form_page_item_fill.page_item');

            if (request('template_id')) {
                $query = $query->whereHas('form', fn ($q) => $q->where('template_id', request('template_id')));
            }


            $data = app(Pipeline::class)->send($query)
                ->through([SortFilters::class])
                ->thenReturn();

            $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 15));

            return responseSuccess($data, 'Form requests retrieved successfully');
        } catch (\Throwable $e) {
            // Return an error response if something goes wrong
            return responseFail($e->getMessage());
        }
    }

    public function getFormRequestfill($id)
    {
        try {
            $formfill = FormRequest::with('form.pages.items', 'user', 'form_page_item_fill')->find($id);

            return responseSuccess($formfill, 'Form requests retrieved successfully');
        } catch (\Throwable $e) {
            // Return an error response if something goes wrong
            return responseFail($e->getMessage());
        }
    }

    public function assignRequest(FormAssign $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                 $formattedDate = Carbon::createFromFormat('Y-m-d', $request->date)->toDateString();
                // check if  form_request_id has record or not
                foreach ($request->form_request_id as $form_request_id) {
                    FormAssignRequest::where('form_request_id', $form_request_id)
                        ->where('status', 'active')
                        ->where('status', '!=', 'deleted')
                        ->update(['status' => 'deleted']);

                    $form_user_id = Form::where('id', $form_request_id)->pluck('user_id');

                     $assignNew = FormAssignRequest::create([
                        'form_request_id' => $form_request_id,
                        'user_id' => $request->user_id,
                        'date' => $formattedDate,
                        'assigner_id' => auth()->id(),
                        'status' => 'active',
                        'form_user_id' => $form_user_id,
                        'type' => FormAssignRequestType::EMPLOYEE,
                    ]);
                    FormRequest::where('id', $form_request_id)->update(['status' => 'processing']);
                }
                $calendarData = [
                    'calendarable_id'=>$assignNew->id,
                    'calendarable_type'=>FormAssignRequest::class,
                    'user_id' => auth()->id(),
                    'date'=>now(),
                ];
                $calendar = saveCalendarFromRequest($calendarData);
                $actionData = [
                    'formable_id'=>$assignNew->id,
                    'formable_type'=>FormAssignRequest::class,
                    'msg'=>'تم اسناد القضيه ل موظف جديد',
                ];
                $calendar = saveFormRequestAction($actionData);
                return responseSuccess(['assignNew' => $assignNew]);
            });
        } catch (Throwable $e) {
            return response()->json(['message' => 'Unknown error', $e], 500);
        }
    }
}
