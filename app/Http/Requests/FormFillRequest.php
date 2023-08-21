<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed name
 * @property mixed pages
 */
class FormFillRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
<<<<<<< HEAD
            'form_request_number' => 'required|integer',
=======
            'form_request_number' => 'nullable|string|unique:forms,form_request_number,' . request()->form_id,
>>>>>>> 64c6778e8643a44d14bb123b7de5b56b8ebae0eb
        ];
    }
}
