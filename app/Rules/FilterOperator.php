<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilterOperator implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $operator = explode('.', $attribute)[1] ?? '';

        return in_array($operator, ['eq', 'lte', 'gte']);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Provided filter operator is not correct.');
    }
}
