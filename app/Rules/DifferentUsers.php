<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DifferentUsers implements Rule
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
        list($claimantId, $defendantId) = explode(',', $value);

        return $claimantId !== $defendantId;
    }

    public function message()
    {
        return 'The claimant and defendant must be different users.';
    }
}
