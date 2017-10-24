<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Phone implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match(
            '/\+?[^\d]{0,7}([0-9]{1})?[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{2})[^\d]{0,7}(\d{2})/',
            $value
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Телефон не соответсвует формату.';
    }
}
