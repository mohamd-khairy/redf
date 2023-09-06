<?php

namespace App\Http\Controllers\API;

use App\Models\Form;
use App\Models\Task;
use App\Models\User;
use App\Models\Court;
use App\Models\Document;
use App\Models\Template;
use App\Models\Department;
use App\Enums\CaseTypeEnum;
use App\Enums\CourtTypeEnum;
use App\Models\Organization;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Specialization;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Symfony\Component\DomCrawler\Crawler;

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

        // array_push($items, [
        //     'id' => count($items) + 1,
        //     'name' => 'المنظمات',
        //     'icon' => 'mdi-scale-balance',
        //     'requests_count' => Organization::count()
        // ]);

        return responseSuccess($items);
    }

    public function lookup()
    {
        $courtTypes = Court::pluck('name')->toArray();
        $branches = Branch::pluck('name')->toArray();
        $specialization = Specialization::pluck('name')->toArray();

        $court_type = [];
        $case_type = [];

        foreach ($courtTypes as $key => $courtType) {
            $court_type[] = [
                'title' => __('enums.' . $courtType),
                'value' => $courtType,
            ];
        }

        foreach (CaseTypeEnum::cases() as $caseTypeValue) {
            $case_type[] = [
                'title' =>  __('enums.' . $caseTypeValue->name),
                'value' => $caseTypeValue->name,
            ];
        }
        return responseSuccess([
            'court_types' => $court_type,
            'case_types' => $case_type,
            'branches' => $branches,
            'specialization' => $specialization,
        ]);
    }
}
