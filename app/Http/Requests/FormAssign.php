<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormAssign extends FormRequest
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
        $id = $this->request->get('id');
        return [
            'form_request_id' => 'required|array',
            'user_id' => 'required',
            // 'assigner_id' => 'required',
            'date' => 'required',
            // 'type' => 'sometimes|in:claimant,employee,defendant,default:employee',
        ];


    }
}
