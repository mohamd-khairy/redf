<?php

namespace App\Rules;

use App\Enums\FormRequestStatus;
use Illuminate\Contracts\Validation\Rule;

class StatusEnumRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
         return in_array($value, FormRequestStatus::getValues());
    }

    public function message()
    {
        return 'Invalid status value.';
    }
}
