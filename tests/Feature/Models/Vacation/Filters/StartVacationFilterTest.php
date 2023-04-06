<?php

namespace Tests\Feature\Models\Vacation\Filters;

class StartVacationFilterTest extends BaseVacationFilters
{
    use DateTimeFilterTestTrait;

    /**
     * @inheritDoc
     */
    protected function getFieldName(): string
    {
        return 'start';
    }

    /**
     * @inheritDoc
     */
    protected function getBaseDataset(): array
    {
        return ['2023-01-01T00:00:00', '2023-06-15T12:30:30', '2023-12-31T23:59:59'];
    }
}
