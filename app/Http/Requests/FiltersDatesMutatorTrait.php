<?php

namespace App\Http\Requests;

use Illuminate\Support\Carbon;

trait FiltersDatesMutatorTrait
{
    /**
     * Converts the given date from JS format.
     *
     * @param string $value
     *
     * @return string
     */
    protected function convertJsDate(string $value): string
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
