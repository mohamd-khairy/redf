<?php

namespace App\Http\Controllers\Api;

use App\Filters\SearchFilters;
use App\Filters\SortFilters;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\TemplateRequest;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Validation\ValidationException;

class TemplateController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth']);
        // $this->middleware('permission:read-department', ['only' => ['index', 'show']]);
        // $this->middleware('permission:create-department', ['only' => ['store']]);
        // $this->middleware('permission:update-department', ['only' => ['update']]);
        // $this->middleware('permission:delete-department', ['only' => ['destroy']]);
    }

    public function getTemplatesType()
    {
        $templates = Template::query()->get();

        return responseSuccess($templates);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PageRequest $request)
    {
        $query = Template::query();

        $data = app(Pipeline::class)->send($query)->through([
            SearchFilters::class,
            SortFilters::class,
        ])->thenReturn();

        $data = request('pageSize') == -1 ?  $data->get() : $data->paginate(request('pageSize',15));

        return responseSuccess(['templates' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TemplateRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;
            // $validatedData['type'] = 'form';
            // Since validation passed, you can directly create the department.
            $template = Template::create($validatedData);
            return responseSuccess($template, 'Template has been successfully created');
        } catch (ValidationException $e) {
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

        $template = Template::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|min:3|max:255',
            // 'user_id' => [
            //     'required',
            //     'integer',
            //     Rule::exists('users', 'id'),
            // ],
        ]);
        $validatedData = $request->all();
        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['type'] = 'form';
        $template->update($validatedData);

        return responseSuccess($template, 'template has been successfully Updated');
    }

    public function show($id)
    {
        $template = Template::findOrFail($id);
        return responseSuccess($template, 'template has been successfully show');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $template = Template::findOrFail($id);
        $template->delete();
        return responseSuccess([], 'template has been successfully deleted');
    }
}
