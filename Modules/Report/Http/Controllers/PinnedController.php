<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\FacadesDB;
use Modules\Report\Entities\DraftChart;
use Modules\Report\Entities\PinnedReport;
use Modules\Report\Filters\GeneralFilters;
use Modules\Report\Http\Requests\PinnedReportRequest;
use Modules\Report\Services\ReportServices\ReportService;

class PinnedController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('permission:read-pinned_report', ['only' => ['index', 'drawPinned']]);
        // $this->middleware('permission:update-pinned_report', ['only' => ['update','edit']]);
        // $this->middleware('permission:delete-pinned_report', ['only' => ['destroy']]);
        // $this->middleware('permission:indashboard-pinned_report', ['only' => ['status']]);
    }

    public function index()
    {
        $page_size = request('pageSize', 15);

        $pinned = app(Pipeline::class)->send(PinnedReport::primary()->with('createdBy')->withCount('charts'))->through([
            GeneralFilters::class
        ])->thenReturn();

        if ($page_size == -1) {
            $pinned = $pinned->get();
        } else {
            $pinned = $pinned->paginate($page_size);
        }

        return responseSuccess([
            'title' => __('dashboard.pinned_reports'),
            'pinneds' => $pinned
        ]);
    }

    public function getRelatedDraft(Request $request)
    {
        $chart_id = $request->chart_id;
        $pinneds = PinnedReport::primary()->latest()->pluck('title', 'id')->toArray();
        $chart_pinneds = DB::table('pinned_charts')->where('chart_id', $chart_id)->pluck('pinned_id')->toArray();

        return responseSuccess([
            'pinneds' => $pinneds,
            'chart_id' => $chart_id,
            'chart_pinneds' => $chart_pinneds,
        ]);
    }

    public function addDraft(Request $request)
    {
        try {
            DB::beginTransaction();

            $titles = array_filter((is_array($request->titles) ? $request->titles : json_decode($request->titles)) ?? []);
            $pinned_ids = array_filter((is_array($request->pinned_ids) ? $request->pinned_ids : json_decode($request->pinned_ids)) ?? []);
            $chart = DraftChart::find($request->chart_id);
            $new_pinned = [];

            if (!empty($titles)) {
                foreach ($titles as $title) {
                    $pinned = PinnedReport::updateOrCreate([
                        'title' => $title ?? Carbon::today()->toDateString() . ' Pinned Report',
                        'user_id' => auth()->user()->id
                    ]);

                    $new_pinned[] = $pinned->id;
                }
            }

            $all_pinned = array_merge($pinned_ids, $new_pinned);

            $chart->pinned()->sync($all_pinned);

            foreach (PinnedReport::pluck('id')->toArray() as $item) {
                cache()->forget("report_pinned_$item");
            }

            DB::commit();

            return response()->json([
                'pinneds' => PinnedReport::select('id', 'title')->primary()->latest()->get(),
                'message' => trans('dashboard.chart_pinned_to_reports_successfully')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return responseFail($e->getMessage());
        }
    }

    public function reload($id): JsonResponse
    {
        cache()->forget("report_pinned_$id");

        return response()->json(['message' => __('dashboard.pinned_updated_successfully')]);
    }

    public function status($id, Request $request)
    {
        try {
            if ($id) {
                $item = PinnedReport::primary()->whereId($id)->update([
                    'active' => true
                ]);

                if ($item) {
                    PinnedReport::primary()->where('id', '<>', $id)->update([
                        'active' => false
                    ]);
                }
            }
            return response()->json([
                'message' => trans('dashboard.report_active_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => trans('dashboard.failed_to_active_report')
            ], 400);
        }
    }

    public function drawPinned($id)
    {
        try {
            $pinned = PinnedReport::where(function ($q) {
                $q->where('user_id', auth()->user()->id)
                    ->orWhere('default', 1);
            })->findOrFail($id);

            // if ($pinned->user_id != auth()->user()->id) {
            //     return responseFail('not belong to you');
            // }

            if ($pinned->global_date) {
                if ($pinned->time_type == 'dynamic') {
                    $date = getStartEndDate($pinned->time_range);
                    $pinned['start'] = $date['start'];
                    $pinned['end'] = $date['end'];
                }
            }

            $report = ReportService::handlePinned($pinned);

            return responseSuccess([
                'title' => __('dashboard.show_title', ['title' => $pinned->title]),
                'report' => $report
            ]);
        } catch (\Exception $e) {
            return responseFail('failed');
        }
    }

    public function update(PinnedReport $pinned, PinnedReportRequest $request)
    {
        if ($pinned->user_id != auth()->user()->id) {
            return responseFail('not belong to you');
        }

        try {
            $data = $request->validated();

            if (!$data['global_date']) {
                $data['start'] = null;
                $data['end'] = null;
                $data['time_type'] = null;
                $data['time_range'] = null;
            } else {
                if ($data['time_type'] == 'dynamic') {
                    $data['start'] = null;
                    $data['end'] = null;
                } else {
                    $data['time_range'] = null;
                }
            }
            cache()->forget("report_pinned_$pinned->id");

            $pinned->update($data);

            return response()->json([
                'message' => trans('dashboard.pinned_updated_successfully')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return responseFail($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $item = PinnedReport::where('id', '!=', 1)->whereId($id)->first();

            if ($item->user_id != auth()->user()->id) {
                return responseFail('not belong to you');
            }

            $item->charts()->detach();
            $item->delete();

            return response()->json([
                'message' => trans('dashboard.pinned_report_delete_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('dashboard.failed_to_delete_row')
            ]);
        }
    }

    public function actions(): JsonResponse
    {
        try {
            $ids = is_array(request('ids', [])) ? request('ids', []) : explode(',', request('ids', ''));

            PinnedReport::where('id', '!=', 1)->whereIn('id', $ids)->delete();

            return responseSuccess([], trans('dashboard.pinned_report_delete_successfully'));
        } catch (\Throwable $th) {
            return responseFail(trans('dashboard.failed_to_delete_row'));
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $item = PinnedReport::primary()->whereId($id)->first();
            if (!$item) {
                return responseFail('not found');
            }

            return responseSuccess($item);
        } catch (\Exception $e) {
            return responseFail($e->getMessage());
        }
    }

    public function active()
    {
        try {
            $pinned = PinnedReport::where('active', true)
                ->where(function ($q) {
                    $q->where('user_id', auth()->user()->id)
                        ->orWhere('default', 1);
                })->first();

            if (!$pinned) {
                return responseFail('no active pinned');
            }

            if ($pinned->global_date) {
                if ($pinned->time_type == 'dynamic') {
                    $date = getStartEndDate($pinned->time_range);
                    $pinned['start'] = $date['start'];
                    $pinned['end'] = $date['end'];
                }
            }

            $report = ReportService::handlePinned($pinned);
        } catch (\Exception $e) {
            // return responseFail('failed');
        }
        return responseSuccess([
            'title' => __('dashboard.show_title', ['title' => $pinned->title ?? '']),
            'report' => $report ?? null
        ]);
    }
}
