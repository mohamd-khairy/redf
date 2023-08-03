<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class FormsController extends Controller
{

    public function store(FormStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->repo->createTemplate($request->name,$request->ar_name, auth()->id(), $request->template_id ?? null, $request->icon,$request->organization_id);
            DB::commit();
            if ($data) {
                return response()->json(new TemplateResource($data));
            } else {
                return response()->json('something went wrong', 500);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
            return response()->json('something went wrong', 500);
        }
    }
}
