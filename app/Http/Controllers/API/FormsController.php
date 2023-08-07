<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Repositories\TemplatesRepository;
use App\Http\Requests\CreateFormRequest;
use App\Http\Requests\FormUpdateRequest;
use App\Http\Resources\FormResource;
use App\Models\Form;
use App\Models\FormPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        // update form
        $form->update([
            'name' => $request->name ?? null,
            'description' => $request->description ?? null,
            'user_id' => auth()->user()->id,
        ]);

        $form->pages()->map->items()->delete();
        $form->pages()->delete();

        foreach ($request->pages as $page) {
            $page = (object)$page;

            // form pages
            $formPage = FormPage::create([
                'form_id' => $form->id,
                'title' => $page->title['title'],
            ]);

            // form items
            foreach ($page->items as $item) {
                $item = (object)$item;

                if ($item->removed == 'true' || $item->removed == true) continue;

                $formPage->items()->create([
                    'type' => $item->type ?? null,
                    'label' => $item->label ?? null,
                    'notes' => $item->notes ?? null,
                    'excel_name' => $item->excel_name ?? null,
                    'width' => $item->width ?? null,
                    'height' => $item->height ?? null,
                    'length' => $item->length ?? null,
                    'input_type' => $item->input_type ?? 'text',
                    'enabled' => filter_var($item->enabled ?? '', FILTER_VALIDATE_BOOLEAN),
                    'required' => filter_var($item->required ?? '', FILTER_VALIDATE_BOOLEAN),
                    'website_view' => filter_var($item->website_view ?? '', FILTER_VALIDATE_BOOLEAN),
                    'childList' => json_encode($item->childList ?? []),
                ]);
            }
        }

        // return form
        return $form->refresh();
    }
}
