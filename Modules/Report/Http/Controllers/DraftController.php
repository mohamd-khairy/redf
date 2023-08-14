<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Models\Department;
use App\Models\Location;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Report\Entities\DraftReport;
use Modules\Report\Entities\PinnedReport;
use Modules\Report\Filters\GeneralFilters;
use Modules\Report\Http\Requests\DraftReportRequest;
use Modules\Report\Services\ReportServices\ConfigService;
use Modules\Report\Services\ReportServices\ReportService;

class DraftController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-draft_report', ['only' => ['index', 'drawDraft']]);
        // $this->middleware('permission:update-draft_report', ['only' => ['update', 'edit']]);
        // $this->middleware('permission:delete-draft_report', ['only' => ['destroy']]);
    }

    public function index(PageRequest $request)
    {
        $data = app(Pipeline::class)->send(DraftReport::primary()->with('createdBy'))->through([
            GeneralFilters::class
        ])->thenReturn();


        $data = $data->paginate($request->pageSize ?? 15);

        return responseSuccess([
            'title' => __('dashboard.draft_reports'),
            'drafts' => $data
        ]);
    }

    public function storeDraft(Request $request): JsonResponse
    {
        try {

            DB::beginTransaction();

            $data = $request->all();

            if ($request->site_ids) {
                $catId = (is_array($request->site_ids) ? $request->site_ids : (is_array(json_decode($request->site_ids)) ? json_decode($request->site_ids) : explode(',', $request->site_ids)));
            } else {
                $catId = [Department::latest()->first('id')->toArray()['id']];
            }

            if ($data['time_type'] == 'dynamic') {
                $data['start'] = null;
                $data['end'] = null;
            } else {
                $data['time_range'] = null;
            }

            $draft = DraftReport::create([
                'start' => $data['start'],
                'end' => $data['end'],
                'model_type' => $request->model_type,
                'report_type' => $request->type ?? 'specific',
                'groupBy' => $request->type == 'comparison' ? 'site_name' : 'date',
                'time_type' => $data['time_type'],
                'time_range' => $data['time_range'],
                'unit' => $request->unit ?? 'number',
                'report_list' => $request->report_list ?? 'total',
                'user_id' => auth()->user()->id,
                'columns' => is_string($request->columns) ? json_decode($request->columns) : $request->columns,
                'site_id' => $catId,
                'title' => $request->title ?? null,
            ]);

            if (!$draft->wasRecentlyCreated) {
                return responseSuccess([
                    'message' => trans('dashboard.this_report_is_already_drafted')
                ]);
            }

            $details = ConfigService::getChartDetails();
            $charts = $request->chart_types ?? [];

            foreach ($charts as $chart) {
                $draft->charts()->create([
                    'type' => $chart,
                    'title' => $details[$chart]['title'],
                    'description' => $details[$chart]['description'],
                    'time_unit' => $details[$chart]['time_unit'],
                ]);
            }

            DB::commit();

            return responseSuccess([
                'message' => trans('dashboard.draft_report_successfully')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return responseFail($e->getMessage());
        }
    }

    public function drawDraft($id)
    {

        $draft = DraftReport::primary()->findOrFail($id);

        if (!$draft) {
            return responseFail('not found');
        }

        try {
            if ($draft->time_type == 'dynamic') {
                $date = getStartEndDate($draft->time_range);
                $draft->start = $date['start'];
                $draft->end = $date['end'];
            }

            $result = ReportService::handleDraft($draft);

            return responseSuccess([
                'title' => "Show $draft->model_type Report",
                'data' => $result['data'],
                'add_pinned' => true,
                'filter' => $result['filter'],
                'charts' => $result['charts'],
                'details' => $result['details'],
            ]);
        } catch (\Exception $e) {
            return responseFail($e->getMessage());
        }
    }

    public function update(DraftReport $draft, DraftReportRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->site_ids) {
                $data['site_id'] = (is_array($request->site_ids) ? $request->site_ids : (is_array(json_decode($request->site_ids)) ? json_decode($request->site_ids) : explode(',', $request->site_ids)));
            }

            if ($request->type) {
                $data['report_type'] = $request->type;
            }

            if ($request->types) {
                $data['columns'] =  (is_array($request->types) ? $request->types : (is_array(json_decode($request->types)) ? json_decode($request->types) : explode(',', $request->types)));
            }

            if ($data['time_type'] == 'dynamic') {
                $data['start'] = null;
                $data['end'] = null;
            } else {
                $data['time_range'] = null;
            }

            $draft->update($data);

            $charts = $request->chart_types ?? [];
            if ($charts) {
                $details = ConfigService::getChartDetails();

                $charts = is_array($charts) ? $charts : json_decode($charts);

                foreach ($charts as $chart) {
                    $draft->charts()->firstOrCreate([
                        'type' => $chart,
                        'title' => $details[$chart]['title'],
                        'description' => $details[$chart]['description'],
                        'time_unit' => $details[$chart]['time_unit'],
                    ]);
                }
            }
            return response()->json([
                'message' => trans('dashboard.draft_updated_successfully')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return responseFail($e->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $item = DraftReport::primary()->whereId($id)->first();
            if (!$item) {
                return responseFail('not found');
            }

            return responseSuccess($item);
        } catch (\Exception $e) {
            return responseFail($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $item = DraftReport::whereId($id)->first();
            if ($item->user_id != auth()->user()->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('dashboard.failed_to_delete_row')
                ]);
            }
            foreach ($item->charts as $chart) {
                foreach ($chart->pinned as $pinned) {
                    cache()->forget("report_pinned_$pinned->id");
                    $pinned->delete();
                }
                $chart->delete();
            }
            $item->charts()->delete();
            $item->delete();

            return response()->json([
                'message' => trans('dashboard.draft_report_delete_successfully')
            ]);
        } catch (\Exception $e) {
            return responseFail($e->getMessage());
        }
    }

    public function actions(): JsonResponse
    {
        try {
            $ids = is_array(request('ids', [])) ? request('ids', []) : explode(',', request('ids', ''));

            $items = DraftReport::whereIn('id', $ids)->get();

            foreach ($items as $item) {
                foreach ($item->charts as $chart) {
                    foreach ($chart->pinned as $pinned) {
                        cache()->forget("report_pinned_$pinned->id");
                        $pinned->delete();
                    }
                    $chart->delete();
                }
                $item->charts()->delete();
                $item->delete();
            }

            return responseSuccess([], trans('dashboard.draft_report_delete_successfully'));
        } catch (\Throwable $th) {
            return responseFail(trans('dashboard.failed_to_delete_row'));
        }
    }
}
