<?php

namespace Tests\Feature\Models\Vacation\Filters;

class PriceVacationFilterTest extends BaseVacationFilters
{
    /**
     * @inheritDoc
     */
    protected function getFieldName(): string
    {
        return 'price';
    }

    /**
     * @inheritDoc
     */
    protected function getBaseDataset(): array
    {
        return [20, 80, 100];
    }

    /**
     * @inheritDoc
     */
    protected function increaseValue($value)
    {
        return $value + 1;
    }

    /**
     * @inheritDoc
     */
    protected function decreaseValue($value)
    {
        return $value - 1;
    }

    /**
     * @inheritDoc
     */
    protected function isGreaterThanOrEqual($value, $comparedTo): bool
    {
        return $value >= $comparedTo;
    }

    /**
     * @inheritDoc
     */
    protected function isLessThanOrEqual($value, $comparedTo): bool
    {
        return $value <= $comparedTo;
    }

    /**
     * @inheritDoc
     */
    protected function parseDatasetValueToMake($value)
    {
        return (float) $value;
    }

    /**
     * @inheritDoc
     */
    protected function isEqualTo($value, $comparedTo): bool
    {
        return (float) $value === (float) $comparedTo;
    }
}
