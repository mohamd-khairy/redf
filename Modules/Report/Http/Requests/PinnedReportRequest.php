<?php

namespace Modules\Report\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;


class PinnedReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:60',
            'global_date' => 'required|in:1,0',
            'time_type' => 'sometimes|nullable|in:dynamic,specific',
            'time_range' => 'sometimes|nullable',
        ];

        if (request('time_type') == 'specific') {
            $rules += [
                'start' => [
                    'required', 'date', 'date_format:Y-m-d', 'before:end'
                ],
                'end' => [
                    'required', 'date', 'date_format:Y-m-d', 'after:start'
                ]
            ];
        }


        return $rules;
    }

    public function messages(): array
    {
        return [
            'site_id.required_if' => trans('dashboard.site_required'),
            'site_ids.required_if' => trans('dashboard.site_required'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(responseFail($validator->errors()->first(), 422));
    }
}
