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
class FormFillRequest extends FormRequest
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
            'type' => 'required|in:related_case,case,consultation',
            'case_number' => 'sometimes|required_if:type,case|regex:/^[0-9]+$/',
            'case_name' => 'sometimes|required_if:type,case|string',
            'case_date' => 'required|date',
            'branche_id' => 'sometimes',
            'pages' => 'required',
            'pages.*' => 'required',
            'pages.*.title' => 'required|string',
            'pages.*.items' => 'required|array',
            'pages.*.items.*' => 'required|array',
            'pages.*.items.*.type' => 'nullable|in:line,radio,label,text,textarea,checkbox,select,table,tree,file',
            'pages.*.items.*.value' => 'required',
            'pages.*.items.*.form_page_item_id' => 'required',
        ];
    }
}
