<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\FormRequestAction;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;


class LogController extends Controller
{
    public $model = Activity::class;

    public function all_logs(){
        $activityLogs = Activity::paginate(request('page_size' , 10));
        return responseSuccess(['activityLogs' => $activityLogs]);
    }
    public function action_preview($id){

        $formRequestActions = FormRequestAction::where('formable_id' , $id)->with(['formable.user'])->get();
        $modifiedData = $formRequestActions->map(function ($action) {
            return [
                'id' => $action->id,
                'msg' => $action->msg,
                'user' => $action->formable->user,
                'form_id' => $action->formable->form,
            ];
        });
        return responseSuccess(['formRequestActions' => $formRequestActions]);

    }
}