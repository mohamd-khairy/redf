<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\FormRequestAction;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;


class LogController extends Controller
{
    public $model = Activity::class;

    public function all_logs()
    {
        $activityLogs = Activity::paginate(request('page_size', 10));

        return responseSuccess(['activityLogs' => $activityLogs]);
    }

    public function action_preview($id)
    {
        $formRequestActions = FormRequestAction::where('form_request_id', $id)->with('formable')->get();

        return responseSuccess(['formRequestActions' => $formRequestActions]);
    }
}
