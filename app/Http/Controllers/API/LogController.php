<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;


class LogController extends Controller
{
    public $model = Activity::class;

    public function all_logs(){
        $activityLogs = Activity::paginate(request('page_size' , 10));
        return responseSuccess(['activityLogs' => $activityLogs]);
    }
}
