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
    public function action_preview(Request $request){
        // $formRequestActions = FormRequestAction::with('formable')->get();
        $formRequestActions = FormRequestAction::whereHas('formable',function($q){
        })->get();
        return responseSuccess(['formRequestActions' => $formRequestActions]);

    }
}
