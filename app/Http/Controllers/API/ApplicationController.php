<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Models\Application;
use App\Models\Stage;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public $model = Application::class;

    public function index(PageRequest $request)
    {
        DB::statement('SET sql_mode = " "');

        $data = Stage::select('stages.*')
            ->join('stage_forms', 'stage_forms.stage_id', '=', 'stages.id')
            ->orderBy('stage_forms.order', 'asc')
            ->groupBy('id')
            ->with('applications.form_request.formAssignedRequests');

        if (request('form_id')) {
            $data = $data->where('stage_forms.form_id', request('form_id'))
                ->with(['applications' => function ($q) {
                    $q->where('form_id', request('form_id'));
                }]);
        }

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 15));

        return responseSuccess($data); //StageResource::collection($data)
    }
}
