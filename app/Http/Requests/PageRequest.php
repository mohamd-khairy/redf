<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'page' => 'nullable|numeric|min:1',
            'pageSize' => 'nullable|min:1',
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->all();

        // Modify or preprocess the input data here
        $input['pageSize'] = request('pageSize') == -1 ? "1000000" : request('pageSize', 15);

        $this->replace($input);
    }
}
