<?php

namespace Tests\Feature\Models\Vacation\Filters;

use App\Models\Vacation;
use Illuminate\Support\Carbon;

trait DateTimeFilterTestTrait
{
    /**
     * @inheritDoc
     */
    protected function increaseValue($value)
    {
        return Carbon::parse($value)->addSecond()->format(Vacation::DATES_FORMAT);
    }

    /**
     * @inheritDoc
     */
    protected function decreaseValue($value)
    {
        return Carbon::parse($value)->subSecond()->format(Vacation::DATES_FORMAT);
    }

    /**
     * @inheritDoc
     */
    protected function isGreaterThanOrEqual($value, $comparedTo): bool
    {
        $parsedValue = Carbon::parse($value);
        $parsedComparedTo = Carbon::parse($comparedTo);

        return $parsedValue->isAfter($parsedComparedTo) || $parsedValue->isSameSecond($parsedComparedTo);
    }

    /**
     * @inheritDoc
     */
    protected function isLessThanOrEqual($value, $comparedTo): bool
    {
        $parsedValue = Carbon::parse($value);
        $parsedComparedTo = Carbon::parse($comparedTo);

        return $parsedValue->isBefore($parsedComparedTo) || $parsedValue->isSameSecond($parsedComparedTo);
    }

    /**
     * @inheritDoc
     */
    protected function parseDatasetValueToMake($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    /**
     * @inheritDoc
     */
    protected function isEqualTo($value, $comparedTo): bool
    {
        return Carbon::parse($value)->isSameSecond(Carbon::parse($comparedTo));
    }
}
