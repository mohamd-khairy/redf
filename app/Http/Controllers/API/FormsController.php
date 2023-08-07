<?php

namespace App\Http\Controllers\API;

use App\Models\Form;
use App\Models\FormPage;
use App\Models\FormRequest;
use App\Models\FormPageItem;
use Illuminate\Http\Request;
use App\Enums\FormRequestStatus;
use App\Models\FormPageItemFill;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\FormResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateFormRequest;
use App\Http\Requests\FormUpdateRequest;
use App\Http\Resources\FormItemResource;
use App\Http\Repositories\TemplatesRepository;

class FormsController extends Controller
{
    protected $repo;

    public function __construct(TemplatesRepository $repo)
    {
        $this->repo = $repo;
        // $this->middleware('permission:list-form|edit-form|create-form|delete-form', ['only' => ['index', 'store']]);
        // $this->middleware('permission:create-form', ['only' => ['store']]);
        // $this->middleware('permission:edit-form', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete-form', ['only' => ['destroy', 'delete_all']]);
    }

    public function allForm(Request $request)
    {
        try {
            $forms = Form::with('template')->paginate(10);
            return responseSuccess($forms);
        } catch (\Throwable $th) {
            throw $th;
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
            throw $th;
        }
    }

    public function showForm($id)
    {
        try {
            $form = Form::find($id);
            return responseSuccess($form);
        } catch (\Throwable $th) {
            throw $th;
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
            throw $th;
        }
    }


    public function update($request, $form)
    {
        $user_id = Auth::id();

        // update form
        $form->update($request->all());

        // Set the user_id in the form
        $form->user_id = $user_id;
        $form->save();

        $form->pages()->each(function ($page) {
            $page->items()->delete();
        });

        $form->pages()->delete();

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


    public function getFormsByTemplate(Request $request)
    {
        try {
            $template_id = $request->template_id;

            if ($template_id) {
                // If template_id is provided, fetch the specific form
                $allForms = Form::where('template_id', $template_id)->get();
            } else {
                // If template_id is not provided, return all forms
                $allForms = Form::all();
                return responseSuccess(FormResource::collection($allForms));
             }

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function listForm($id){

        try {
            $form = Form::find($id);
            if (!$form) {
                return responseFail('there is no form with this id');
            }
            return responseSuccess(new FormItemResource($form));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function storeFormFill(Request $request){

        $form_id = $request->id;

        $formRequest = FormRequest::create([
            'form_id' => $form_id,
            'user_id' => auth()->user()->id,
            'status' => FormRequestStatus::PENDING, // Set the initial status to "pending"
        ]);

         // collect(request('pages'))
        // ->pluck("items")
        // ->each(fn($item) => FormPageItemFill::insert($item));


        $pages = $request->input('pages', []);

        foreach ($pages as $page) {
            $pageItems = $page['items'] ?? [];

            foreach ($pageItems as $pageItem) {
                // Assuming you have the 'value' field in the request
                $value = $pageItem['value'] ?? null;
                $formPageItemFill = new FormPageItemFill([
                    'value' => $value,
                    'form_page_item_id' => $pageItem['form_page_id'],
                    'user_id' => auth()->user()->id,
                    'form_request_id' => $formRequest->id,
                ]);
                $formPageItemFill->save();
            }
        }

        return responseSuccess([], 'Form Fill has been successfully deleted');


     }
}
