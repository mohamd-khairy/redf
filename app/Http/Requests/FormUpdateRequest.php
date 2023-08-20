<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed name
 * @property mixed pages
 */
class FormUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
           'name' => 'nullable|string|unique:forms,name,' . request()->id,
           'pages' => 'required|array',
           'pages.*' => 'required|array',
           'pages.*.title.title' => 'required|string',
           'pages.*.title.editing' => 'required|boolean',
           'pages.*.items' => 'required|array',
           'pages.*.items.*' => 'required|array',
           'pages.*.items.*.type' => 'nullable|in:line,radio,label,text,textarea,checkbox,select,table,tree,file',
           'pages.*.items.*.label' => 'nullable|string',
           'pages.*.items.*.excel_name' => 'nullable|string',
           'pages.*.items.*.notes' => 'nullable|string',
           'pages.*.items.*.width' => 'nullable',
           'pages.*.items.*.height' => 'nullable',
           'pages.*.items.*.enabled' => 'nullable',
           'pages.*.items.*.required' => 'nullable',
           'pages.*.items.*.website_view' => 'nullable',
           'pages.*.items.*.childList' => 'sometimes|array|nullable',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'pages.*.items.required' => 'Cannot add pages without any elements',
        ];
    }
}