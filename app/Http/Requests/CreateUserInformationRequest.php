<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserInformationRequest extends FormRequest
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
            'civil_number' => 'required|integer|unique:user_information|digits:10', // Set max to 10 digits
            'name' => 'required|alpha',
            'phone' => 'required|integer|digits:10|regex:/^[1-9]+$/', // Add phone rule
            'email' => 'nullable|email', // Allow nullable email
            'department_id' => 'nullable|exists:departments,id',
        ];
    }

    public function validatedWithDefaults()
    {
        $validated = $this->validated();
        // If email is not provided, generate a random email
        if (!isset($validated['email'])) {
            $validated['email'] = Str::random(10) . '@example.com';
        }

        return $validated;
    }
}
