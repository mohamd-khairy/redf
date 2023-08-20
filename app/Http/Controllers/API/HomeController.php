<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Task;
use App\Models\Template;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data = Template::select('id', 'name', 'icon')->withCount('requests')->get();

        $items = $data->toArray();

        array_push($items, [
            'id' => $data->count() + 1,
            'name' => 'المهام',
            'icon' => 'mdi-scale-balance',
            'requests_count' => Task::count()
        ]);
        return responseSuccess($items);
    }
}
