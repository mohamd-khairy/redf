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
            'percentage' => 'nullable|numeric',
            // 'details' => 'sometimes|string',
            'date' => 'required|date',
            'status' => 'nullable',
            'court' => 'nullable',
        ];
    }
}
