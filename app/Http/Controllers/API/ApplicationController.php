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
        $data = StageForm::query()
            ->with('stage.applications.form_request')
            ->with(['stage.applications' => function ($q) {
                if (request('form_id')) {
                    $q->where('form_id', request('form_id'));
                }
            }]);

        if (request('form_id')) {
            $data->where('form_id', request('form_id'));
        }

        $data = $data->orderBy('order', 'asc');

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize', 15));

        return responseSuccess(StageResource::collection($data));
    }
}
