<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Enum;
use App\Enums\TreatmentInformationTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class TreatmentInformationRequest extends FormRequest
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

    public function rules()
    {
        // Define validation rules dynamically based on the keys in the request
        $rules = [
            'treatment_id' => 'required|exists:treatments,id',
            'date' => 'required|date',
        ];

        $data = $this->all();

        foreach ($data as $key => $value) {
            if ($key !== 'treatment_id' && $key !== 'date') {
                $rules[$key] = 'required|string';
            }
        }

        return $rules;

        // $rules = [
        //     // 'treatment_id' => 'required|exists:treatments,id',
        //     // 'key' => [new Enum(TreatmentInformationTypeEnum::class)],
        //     // 'value' => 'required',
        //     // 'date' => 'required|date',
        //     'treatment_id' => 'required|exists:treatments,id',
        //     'date' => 'required|date',
        // ];

        // // For the 'update' method, make the fields optional
        // if ($this->isMethod('patch') || $this->isMethod('put')) {
        //     $rules['treatment_id'] = 'nullable';
        //     $rules['key'] = 'nullable';
        //     $rules['value'] = 'nullable';
        //     $rules['date'] = 'nullable';
        // }

        // return $rules;
    }
}