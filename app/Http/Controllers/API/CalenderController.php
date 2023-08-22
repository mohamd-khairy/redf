<?php

namespace App\Http\Controllers\Api;

use App\Models\Calendar;
use App\Filters\SortFilters;
use Illuminate\Http\Request;
use App\Filters\SearchFilters;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\PageRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\CalendarRequest;
use Throwable;

class CalenderController extends Controller
{
    public $model = Branch::class;

    public function index(PageRequest $request)
    {
        $user = auth()->user();
        if ($user->hasRole('root')) {
            // Logic for root user
            $calendars = Calendar::query();
        } else {
            // Logic for regular user
            $calendars = $user->calenders()->getQuery();
        }
        $data = app(Pipeline::class)->send($calendars)->through([
            SortFilters::class,
        ])->thenReturn();

        $data = $data->paginate(request('pageSize', 15));

        return responseSuccess(['calendars' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CalendarRequest $request)
    {

        try {
            $data = $request->validated();
            $calendar = Calendar::create($data + ['user_id' => auth()->id()]);
            return responseSuccess($calendar, 'Calender has been successfully created');
        } catch (Throwable $e) {
            // If validation fails, handle the validation errors here.
            return responseFail($e->getMessage());
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $calender = Calendar::findOrFail($id);

        $request->validate([
            'calendarable_type' => 'sometimes|string',
            'calendarable_id' => 'sometimes|integer',
            'date' => 'sometimes|date',
            'details' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        $calender->update($request->all());

        return responseSuccess($calender, 'calender has been successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $calendar = Calendar::findOrFail($id);
        $calendar->delete();
        return responseSuccess([], 'calendar has been successfully deleted');
    }
}
