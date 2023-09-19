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
use App\Enums\FormRequestStatus;
use App\Models\Organization;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Calendar;
use App\Models\Reminder;
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
            'name' => 'الوثائق',
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
            'name' => 'التصنيفات',
            'icon' => 'mdi-scale-balance',
            'requests_count' => Organization::count()
        ]);

        array_push($items, [
            'id' => count($items) + 1,
            'name' => 'المحاكم',
            'icon' => 'mdi-scale-balance',
            'requests_count' => Branch::count()
        ]);

        array_push($items, [
            'id' => count($items) + 1,
            'name' => 'الدوائر',
            'icon' => 'mdi-scale-balance',
            'requests_count' => Specialization::count()
        ]);

        return responseSuccess($items);
    }

    public function lookup()
    {
        $courtTypes = Court::pluck('name')->toArray();
        $branches = Branch::pluck('name')->toArray();
        $specialization = Specialization::get();
        $organizations = Organization::get();

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

        foreach (FormRequestStatus::cases() as $value) {
            $request_status[] = [
                'title' =>  $value->value,
                'value' => $value->name,
            ];
        }
        return responseSuccess([
            'request_status' => $request_status,
            'court_types' => $court_type,
            'case_types' => $case_type,
            'branches' => $branches,
            'specialization' => $specialization,
            'organizations' => $organizations,
        ]);
    }

    public function reminders()
    {
        $calender = Calendar::with('calendarable')->get();
        $data = Reminder::get();

        $all = [];

        foreach ($calender as $key => $value) {
            $all[] = [
                'name' => $value->details,
                'color' => "#" . dechex(rand(0x000000, 0xFFFFFF)),
                'start_date' => $value->date,
                'end_date' => $value->date,
                'form_request_id' => $value->form_request_id
            ];
        }

        foreach ($data as $key => $value) {
            $all[] = [
                'name' => $value->name,
                'color' => $value->color,
                'start_date' => $value->start_date,
                'end_date' => $value->end_date,
                'form_request_id' => $value->form_request_id
            ];
        }
        return responseSuccess($all);
    }
}
