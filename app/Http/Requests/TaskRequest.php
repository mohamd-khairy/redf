<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'status' => 'required', // Enum values
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'assigner_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'details' => 'nullable|string',
            'share_with' => 'nullable|string',
            'form_request_id' => 'nullable|exists:form_requests,id',
            // 'files' => 'required',
            'stage_id' => 'nullable|integer'
        ];
    }
}
