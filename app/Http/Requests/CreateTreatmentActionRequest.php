<?php

namespace App\Http\Requests;

use App\Enums\TreatmentActionEnum;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class CreateTreatmentActionRequest extends FormRequest
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
            'treatment_id' => 'required|exists:treatments,id',
            'user_id' => 'required|exists:users,id',
            'type' => [new Enum(TreatmentActionEnum::class)],
            'text' => 'required|string',
        ];
    }
}
