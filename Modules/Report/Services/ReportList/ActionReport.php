<?php

namespace Modules\Report\Services\ReportList;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ActionReport extends BaseReport
{
    public $noticeQuery;
    public $notNoticeQuery;

    public function prepare($filter, $catId, bool $timeline = false): void
    {
        $filter['catId'] = $catId;

        if ($timeline) {
            $filter['format_date'] = $this->guessDateFormat($filter['start'], $filter['end']);
            $noticeQuery = $this->prepareTimeLineQuery($filter, 'notice');
            $notNoticeQuery = $this->prepareTimeLineQuery($filter, 'not_notice');
        } else {
            $noticeQuery = $this->prepareBaseQuery($filter, 'notice');
            $notNoticeQuery = $this->prepareBaseQuery($filter, 'not_notice');
        }

        $this->noticeQuery = $noticeQuery;
        $this->notNoticeQuery = $notNoticeQuery;
    }

    /**
     * @throws \JsonException
     */
    public function getReport($filter): array
    {
        $filter['column'] = "$this->mainTable.$this->tableDay";

        $noticeQuery = $this->handleDateFilter($this->noticeQuery, $filter);
        $notNoticeQuery = $this->handleDateFilter($this->notNoticeQuery, $filter);

        $notice = json_decode($noticeQuery->groupBy($filter['groupBy'])
            ->get()
            ->mapWithKeys(function ($item) use ($filter) {
                return [$item->{$filter['groupBy']} => $item];
            }), true, 512, JSON_THROW_ON_ERROR);

        $notNotice = json_decode($notNoticeQuery->groupBy($filter['groupBy'])
            ->get()
            ->mapWithKeys(function ($item) use ($filter) {
                return [$item->{$filter['groupBy']} => $item];
            }), true, 512, JSON_THROW_ON_ERROR);

        $result = array_merge_recursive_distinct($notice, $notNotice);

        $columns = $filter['columns']['data'];

        return collect($result)->map(function ($item) use ($columns) {
            foreach ($columns as $column) {
                if (!isset($item[$column])) {
                    $item[$column] = 0;
                }
            }
            return $item;
        })->toArray();
    }

    /**
     * @param $filter
     * @param $type
     * @return Builder
     */
    private function prepareBaseQuery($filter, $type): Builder
    {
        DB::statement('SET sql_mode = " "');

        $query = DB::table($this->mainTable)
            ->whereIn('location_id', Arr::wrap($filter['catId']))
            ->where("$this->mainTable.notice_time", $type !== 'notice' ? '=' : '<>', null)
            ->join('locations', 'locations.id', '=', "$this->mainTable.location_id");

        $query->select(
            "locations.name as {$filter['groupBy']}",
            DB::raw("COUNT($this->mainTable.id) as $type")
        );

        return $query;
    }

    /**
     * @param $filter
     * @param $type
     * @return Builder
     */
    private function prepareTimeLineQuery($filter, $type): Builder
    {
        DB::statement('SET sql_mode = " "');

        return DB::table($this->mainTable)
            ->whereIn('location_id', Arr::wrap($filter['catId']))
            ->where("$this->mainTable.notice_time", $type !== 'notice' ? '=' : '<>', null)
            ->select(
                DB::raw("(DATE_FORMAT($this->mainTable.$this->tableDay, '{$filter['format_date']}')) as {$filter['groupBy']}"),
                DB::raw("COUNT($this->mainTable.id) as $type")
            );
    }
}
