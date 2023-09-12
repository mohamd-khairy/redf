<?php

namespace App\Http\Controllers;

use App\Models\FormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function actions()
    {
        $ids = is_array(request('ids', [])) ? request('ids', []) : explode(',', request('ids', ''));

        $model = $this->model;

        if ($model) {

            $item = $model::whereIn('id', $ids);

            if ($model == FormRequest::class) {

                foreach ($item->get() as $key => $request) {
                    # code...

                    $request->calenders()->delete();
                    $request->formRequestActions()->delete();
                    $request->formRequestInformations()->delete();
                    $request->formRequestSide()->delete();
                    $request->tasks()->delete();
                    $request->form_page_item_fill()->delete();
                    if ($request->type == 'case') {
                        FormRequest::whereIn('id', $request->requests()->pluck('formable_id'))->delete();
                        $request->requests()->delete();
                    }
                    // Delete the FormRequest itself
                    $request->delete();
                }
            } else {
                $item->delete();
            }
        }

        return responseSuccess([], 'action set successfully');
    }
}
