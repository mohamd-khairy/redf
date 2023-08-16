<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarRequest extends FormRequest
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
            'calendarable_type' => 'nullable|string',
            'calendarable_id' => 'nullable|integer',
            'date' => 'required|date',
            'details' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',

        ];
    }
}
