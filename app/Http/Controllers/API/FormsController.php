<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Repositories\TemplatesRepository;
use App\Http\Requests\CreateFormRequest;
use App\Http\Resources\FormResource;
use App\Models\Form;
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

    // public function store(CreateFormRequest $request)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $data = $this->repo->createTemplate($request->name, auth()->id(), $request->template_id ?? null, $request->icon, $request->organization_id);
    //         DB::commit();
    //         if ($data) {
    //             return responseSuccess(new FormResource($data));
    //         } else {
    //             return responseFail('something went wrong');
    //         }
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         return responseFail('something went wrong');
    //     }
    // }

    // public function updateTemplate(Request $request)
    // {
    //     try {
    //         if ($this->repo->getModelClass()->findOrFail($request->id))
    //             $res = $this->repo->updateTemplate($request->id, $request->name, $request->ar_name, $request->icon, $request->organization_id);
    //         return $res;
    //     } catch (Exception $e) {
    //         if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
    //             return response()->json(['errors' => ['name' => ['This name already exists']]], 422);
    //         }
    //         return response()->json(['message' => 'Unknown error', $e], 500);
    //     }
    // }

    // public function update(Template $template, TemplateUpdateRequest $request)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $data = $this->repo->update($request, $template);
    //         DB::commit();

    //         if ($data) {
    //             return response()->json(new TemplateResource($template->refresh()));
    //         } else {
    //             return response()->json('something went wrong', 500);
    //         }
    //     } catch (\Exception $e) {
    //         return $e;
    //         //            return response()->json('something went wrong', 500);
    //     }
    // }

    // public function destroy(Request $request)
    // {
    //     try {
    //         foreach ($request->ids as $tempId) {
    //             $tempName = Template::where('id', $tempId)->pluck('name');
    //             Permission::where('name', $tempName)->delete();
    //         };
    //         return $this->repo->bulkDelete($request->ids ?? [], false);
    //     } catch (Exception $e) {
    //         // return [$request->ids,$e->getMessage()];
    //         return response()->json(['message' => 'Unknown error'], 500);
    //     }
    // }
    // public function restore(Request $request)
    // {
    //     try {
    //         return $this->repo->bulkRestore($request->ids ?? [], false);
    //     } catch (Exception $e) {
    //         // return [$request->ids,$e->getMessage()];
    //         return response()->json(['message' => 'Unknown error'], 500);
    //     }
    // }

    // public function assign(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         //            return $request->all();
    //         $data = $this->repo->assignAdmin($request->template_id, $request->selectedAdmins);
    //         DB::commit();
    //         if ($data)
    //             return response()->json(['data' => $data, 'message' => 'successfully assigned to user', 'code' => 200], 200);
    //         else
    //             return response()->json(['data' => [], 'message' => 'bad request', 'code' => 400], 400);
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['message' => $e->getMessage()], 500);
    //     }
    // }

    // public function getUserOrganization(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $data = $this->repo->getUsersOfOrganizations($request->organization_id);
    //         DB::commit();
    //         if ($data)
    //             return response()->json(['data' => $data, 'message' => 'successfully get users', 'code' => 200], 200);
    //         else
    //             return response()->json(['data' => [], 'message' => 'bad request', 'code' => 400], 400);
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['message' => $e->getMessage()], 500);
    //     }
    // }
}
