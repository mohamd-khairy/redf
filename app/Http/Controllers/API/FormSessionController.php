<?php

namespace App\Http\Controllers\Api;

use App\Models\FormSession;
use App\Filters\SortFilters;
use Illuminate\Http\Request;
use App\Filters\SearchFilters;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\PageRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormSessionRequest;

class FormSessionController extends Controller
{
    public function index(PageRequest $request)
    {
        $formSessions = FormSession::query();

        $data = app(Pipeline::class)->send($formSessions)->through([

            SortFilters::class,
        ])->thenReturn();

        $data = $data->paginate($request->pageSize ?? 15);

        return responseSuccess(['formSessions' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormSessionRequest $request)
    {
        try {
            $validatedData = $request->validated();
            // Since validation passed, you can directly create the formSession.
            $formSession = FormSession::create($validatedData);
            $calendarData = [
                'calendarable_id'=>$formSession->id,
                'calendarable_type'=>FormSession::class,
                'user_id' => auth()->id(),
                'date'=>now(),
            ];
            $calendar = saveCalendarFromRequest($calendarData);
            $actionData = [
                'formable_id'=>$formSession->id,
                'formable_type'=>FormSession::class,
                'msg'=>'تم اضافه جلسه ',
            ];
            $calendar = saveFormRequestAction($actionData);
            return responseSuccess($formSession, 'FormSession has been successfully created');
        } catch (\Throwable $e) {
            // If validation fails, handle the validation errors here.
            return responseFail($e->getMessage());
        }
    }

    public function show($id)
    {
        $formSession = FormSession::findOrFail($id);
        return responseSuccess($formSession, 'FormSession has been successfully showed');
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
        $formSession = FormSession::findOrFail($id);

        $request->validate([
            'form_id' => 'sometimes|exists:forms,id',
            'date' => 'sometimes|date',
            'status' => 'sometimes|string',
            'details' => 'sometimes|string',
        ]);

        $formSession->update($request->all());

        return responseSuccess($formSession, 'FormSession has been successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $formSession = FormSession::findOrFail($id);
        $formSession->delete();
        return responseSuccess([], 'FormSession has been successfully deleted');
    }
}
