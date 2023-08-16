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
        $data = Template::select('id', 'name', 'icon')->withCount('requests')->get();

        return responseSuccess($data);
    }
}
