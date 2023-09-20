<?php

namespace App\Http\Controllers\API;

use App\Filters\SearchFilters;
use App\Filters\SortFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Http\Resources\StageResource;
use App\Models\Application;
use App\Models\Stage;
use App\Models\StageForm;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class ApplicationController extends Controller
{
    public $model = Application::class;

    public function index(PageRequest $request)
    {

        $data = Stage::with('applications.form_request');

        if (request('form_id')) {
            $data = $data->with(['applications' => function ($q) {
                $q->where('form_id', request('form_id'));
            }])->whereHas('stage_forms', function ($q) {
                $q->where('form_id', request('form_id'))
                    ->orderBy('order', 'asc');
            });
        }

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 15));

        return responseSuccess(StageResource::collection($data));
    }
}
