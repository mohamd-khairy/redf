<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformationRequest extends FormRequest
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
            'form_request_id' => 'nullable|exists:form_requests,id',
            'amount' => 'nullable|numeric',
            'user_id' => 'nullable',
            'percentage' => 'nullable|numeric',
            'details' => 'nullable|string',
            'date' => 'nullable|date',
            'status' => 'nullable',
            'court' => 'nullable|in:first_degree,appeal,supreme,implementation',
            'sessionDate' => 'nullable|date',
            'session_place' => 'nullable',
            'date_of_receipt' => 'nullable|date',
        ];
    }
}
