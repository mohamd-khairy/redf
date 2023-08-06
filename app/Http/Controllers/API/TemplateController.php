<?php

namespace App\Http\Controllers\Api;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\TemplateRequest;
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'page' => 'nullable|numeric|min:1',
            'pageSize' => 'nullable|min:1',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }
        $page_size = request('pageSize', 15);
        $templates = Template::query();
        if (request('search')) {
            $templates = $templates->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%');
            });
        }
        if ($page_size == -1) {
            $templates = $templates->get();
        } else {
            $templates = $templates->paginate($page_size);
        }

        return responseSuccess(['templates' => $templates]);
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
            $validatedData['type'] = 'form';
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
