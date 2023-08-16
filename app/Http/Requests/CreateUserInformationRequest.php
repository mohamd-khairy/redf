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
            'civil_number' => 'required|integer',
            'name' => 'required|string', // Add name rule
            'phone' => 'required|integer', // Add phone rule
            'email' => 'nullable|email', // Allow nullable email

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
