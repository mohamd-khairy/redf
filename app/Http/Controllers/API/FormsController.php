<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Form;
use App\Models\FormPage;
use App\Models\FormRequest;
use App\Filters\SortFilters;
use App\Models\FormPageItem;
use Illuminate\Http\Request;
use App\Models\AssignRequest;
use App\Enums\FormRequestStatus;
use App\Models\FormPageItemFill;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\FormResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateFormRequest;
use App\Http\Requests\FormAssignRequest;
use App\Http\Requests\FormUpdateRequest;
use App\Http\Resources\FormItemResource;

class FormsController extends Controller
{

    public function __construct()
    {
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
        // update form
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $form->update($data);

        // delete old form pages
        $form->pages()->each(function ($page) {
            $page->items()->delete();
        });

        $form->pages()->delete();

        // create new form pages with new elements
        $pagesData = $request->input('pages');
        foreach ($pagesData as $pageData) {
            $page = new FormPage([
                'title' => $pageData['title']['title'],
                'editable' =>  $pageData['title']['editable'] == false ? 0 : 1
            ]);
            $form->pages()->save($page);

            if (isset($pageData['items']) && is_array($pageData['items'])) {
                foreach ($pageData['items'] as $itemData) {
                    // Serialize the 'childList' array to a JSON string
                    $item = new FormPageItem([
                        'type' => $itemData['type'],
                        'label' => $itemData['label'],
                        'notes' => $itemData['notes'],
                        'width' => $itemData['width'],
                        'height' => $itemData['height'],
                        'enabled' => $itemData['enabled'],
                        'required' => $itemData['required'],
                        'website_view' => $itemData['website_view'],
                        'childList' => isset($itemData['childList']) ? json_encode($itemData['childList']) : null // Save the serialized string
                    ]);
                    $page->items()->save($item);
                }
            }
        }

        return $form->refresh();
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
            if ($template_id) {
                // If template_id is provided, fetch the specific form
                $allForms = Form::where('template_id', $template_id)->get();
            } else {
                // If template_id is not provided, return all forms
                $allForms = Form::get();
            }
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
            DB::beginTransaction();

            $form_id = $request->id;
            $formRequest = FormRequest::create([
                'form_id' => $form_id,
                'user_id' => auth()->user()->id,
                'status' => FormRequestStatus::PENDING, // Set the initial status to "pending"
            ]);


            $pages = $request->input('pages', []);
            foreach ($pages as $page) {

                $pageItems = $page['items'] ?? [];
                foreach ($pageItems as $pageItem) {
                    $formPageItemFill = new FormPageItemFill([
                        'value' => $pageItem['value'] ?? null,
                        'form_page_item_id' => $pageItem['form_page_item_id'],
                        'user_id' => auth()->user()->id,
                        'form_request_id' => $formRequest->id,
                    ]);
                    $formPageItemFill->save();
                }
            }
            DB::commit();
            return responseSuccess([], 'Form Fill has been successfully deleted');
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseFail($th->getMessage());
        }
    }

    public function getFormRequest(Request $request)
    {
        try {
            $query = FormRequest::with('form.pages.items', 'user', 'form_page_item_fill')
                ->whereHas('form', function ($q) use ($request) {
                    $q->where('template_id', $request->template_id);
                });

            $formRequests = app(Pipeline::class)->send($query)->through([
                SortFilters::class,
            ])->thenReturn();

            $formRequests = $formRequests->paginate(request('page_size', 10));

            return responseSuccess($formRequests, 'Form requests retrieved successfully');
        } catch (\Throwable $e) {
            // dd($e->getMed);
            // Return an error response if something goes wrong
            return responseFail($e->getMessage());
        }
    }

    public function assignRequest(FormAssignRequest $request)
    {
        try {
            $form_request_ids = $request->form_request_id;
            $dateFromRequest = $request->date;
            $formattedDate = Carbon::createFromFormat('Y-m-d', $dateFromRequest)->toDateString();
             // check if  form_request_id has record or not
            foreach ($form_request_ids as $form_request_id) {
                $checkIfAssigned = AssignRequest::where('form_request_id', $form_request_id)->where('status','active')->first();

                if ($checkIfAssigned) {
                    if ($checkIfAssigned->status !== "deleted") {
                        $checkIfAssigned->update([
                            'status' => "deleted",
                        ]);
                    }
                }
                $form_user_id = Form::where('id', $form_request_id)->pluck('user_id');

                $assignNew = AssignRequest::create([
                    'form_request_id' => $form_request_id,
                    'user_id' => $request->user_id,
                    'date' => $formattedDate,
                    'assigner_id' => Auth::user()->id,
                    'status' => 'active',
                    'form_user_id' => $form_user_id,
                ]);
                // dd(FormRequest::where('id', $form_request_id)->get());
                FormRequest::where('id', $form_request_id)->update(['status' => 'processing']);
            }
            return responseSuccess(['assignNew' => $assignNew]);


        } catch (Exception $e) {
            return $e;
            return response()->json(['message' => 'Unknown error', $e], 500);
        }

    }
}
