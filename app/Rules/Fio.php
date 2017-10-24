<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Fio implements Rule
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
        return (bool) preg_match(
            '/^[А-ЯЁ]{1}[а-яё]{2,20}[ ]{1}[А-ЯЁ]{1}[а-яё]{2,20}[ ]{1}[А-ЯЁ]{1}[а-яё]{2,20}[^ь]+$/u',
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
        return 'ФИО не соответсвует формату.';
    }
}
