<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Template;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // $templates = Template::with(['taskCount'])->get();

        // foreach ($templates as $template) {
        //     $taskCount = $template->taskCount->sum('aggregate');

        //     // You can now use $taskCount for each template
        //     // For example: $template->name, $template->icon, $taskCount
        // }
        $data = Template::select('id', 'name', 'icon')->withCount('requests')->get();

        return responseSuccess($data);
    }
}
