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
class UpdateFormFillRequest extends FormRequest
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
        $data =  [
            'type' => 'required|in:related_case,case,consultation',
            'pages' => 'required',
            'pages.*' => 'required',
            'pages.*.title' => 'required|string',
            'pages.*.items' => 'required|array',
            'pages.*.items.*' => 'required|array',
            'pages.*.items.*.type' => 'nullable|in:line,radio,label,text,textarea,checkbox,select,table,tree,file',
            'pages.*.items.*.value' => 'required',
            'pages.*.items.*.form_page_item_id' => 'required',
        ];

        if (request('type') == 'case') {
            $data['case_number'] = 'required|regex:/^[0-9]+$/';
            $data['case_name'] = 'required|string';
            $data['case_date'] = 'required|date';
            $data['branche_id'] = 'required';
            $data['specialization_id'] = 'required';
            $data['organization_id'] = 'required';
            $data['case_type'] = 'required';
            $data['file'] = 'nullable';
            $data['department_id'] = 'nullable';
        }

        if (request('type') == 'related_case') {
            $data['case_id'] = 'required';
        }

        return $data;
    }
}
