<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\AboutUs;

class CheckaboutImage implements Rule
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
        return 'required';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The about me image field is required.';
    }
}
