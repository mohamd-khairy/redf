<?php

namespace App\Http\Controllers\API;

use App\Models\Form;
use App\Models\FormPage;
use App\Models\FormPageItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\FormResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateFormRequest;
use App\Http\Requests\FormUpdateRequest;
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
            $forms = Form::paginate(10);
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
            return responseSuccess(new FormResource($form));
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function update($request, $form)
    {
        // dd($request->all());
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
        // $form->pages()->map->items()->delete();
        // $form->pages()->delete();

        $pagesData = $request->input('pages');

        foreach ($pagesData as $pageData) {

            $editable =  $pageData['title']['editable'] == false ? 0 :1 ;

              $page = new FormPage([
                'title' => $pageData['title']['title'],
                'editable' => $editable,
             ]);
            $form->pages()->save($page);

            if (isset($pageData['items']) && is_array($pageData['items'])) {
                foreach ($pageData['items'] as $itemData) {
                     // Serialize the 'childList' array to a JSON string
                    $childList = isset($itemData['childList']) ? json_encode($itemData['childList']) : null;
                    $item = new FormPageItem([
                        'type' => $itemData['type'],
                        'label' => $itemData['label'],
                        'notes' => $itemData['notes'],
                         'width' => $itemData['width'],
                        'height' => $itemData['height'],
                        'enabled' => $itemData['enabled'],
                        'required' => $itemData['required'],
                        'website_view' => $itemData['website_view'],
                        'childList' => $childList, // Save the serialized string
                    ]);
                    $page->items()->save($item);
                }
            }
        }

        return $form->refresh();
    }
}
