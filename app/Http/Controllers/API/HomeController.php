<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Document;
use App\Models\Form;
use App\Models\Organization;
use App\Models\Task;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data = Template::select('id', 'name', 'icon')->withCount('requests')->get();

        $items = $data->toArray();

        array_push($items, [
            'id' => count($items) + 1,
            'name' => 'المستفيدون',
            'icon' => 'mdi-scale-balance',
            'requests_count' => User::whereHas('roles', function ($q) {
                $q->where('name', '!=', 'root')->where('name', '!=', 'admin');
            })->whereNot('type', 'employee')->count()
        ]);

        array_push($items, [
            'id' => count($items) + 1,
            'name' => 'المهام',
            'icon' => 'mdi-scale-balance',
            'requests_count' => Task::count()
        ]);

        array_push($items, [
            'id' => count($items) + 1,
            'name' => 'المستندات',
            'icon' => 'mdi-scale-balance',
            'requests_count' => Document::count()
        ]);

        array_push($items, [
            'id' => count($items) + 1,
            'name' => 'الادارات',
            'icon' => 'mdi-scale-balance',
            'requests_count' => Department::count()
        ]);

        array_push($items, [
            'id' => count($items) + 1,
            'name' => 'المنظمات',
            'icon' => 'mdi-scale-balance',
            'requests_count' => Organization::count()
        ]);

        return responseSuccess($items);
    }
}
