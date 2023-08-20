<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormSessionRequest extends FormRequest
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
            'form_request_id' => 'required|exists:forms,id',
            'date' => 'required|date',
            'status' => 'required|string',
            'details' => 'nullable|string',
        ];
    }
}
