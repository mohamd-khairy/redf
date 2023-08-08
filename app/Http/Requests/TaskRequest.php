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
            'title' => 'required|string|max:255',
            'type' => 'required', // Enum values
            'user_id' => 'required|exists:users,id',
            'assigner_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'details' => 'nullable|string',
            'document_id' => 'required|exists:documents,id',
            'share_with' => 'nullable|string',
            'form_id' => 'nullable|exists:forms,id',
        ];
    }
}
