<?php

namespace App\Rules\Vacation;

use App\Models\Vacation;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class DateFormat implements Rule
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
        $validator = Validator::make([
            'date' => $value,
        ], [
            'date' => 'date_format:' . Vacation::DATES_FORMAT,
        ]);

        return $validator->passes();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Provided date has invalid format.');
    }
}
