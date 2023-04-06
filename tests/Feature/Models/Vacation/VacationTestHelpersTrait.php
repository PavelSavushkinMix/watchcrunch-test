<?php

namespace Tests\Feature\Models\Vacation;

use App\Models\Vacation;
use Illuminate\Support\Collection;

trait VacationTestHelpersTrait
{
    /**
     * Creates the vacation(s) with the provided custom fields.
     *
     * @param int $number
     * @param array $customFields
     *
     * @return Collection
     */
    protected function makeVacations(int $number, array $customFields = []): Collection
    {
        return Vacation::factory($number)->create($customFields);
    }
}
