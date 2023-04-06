<?php

namespace Tests\Feature\Models\Vacation\Filters;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\Feature\Models\ModelTestsHelpersTrait;
use Tests\TestCase;

abstract class BaseVacationFilters extends TestCase
{
    use RefreshDatabase, \Tests\Feature\Models\Vacation\VacationTestHelpersTrait, ModelTestsHelpersTrait;

    /**
     * @var Collection
     */
    protected $items;

    /**
     * @return void
     */
    public function test_field_is_not_array()
    {
        $field = $this->getFieldName();

        $response = $this->makeRequest([$field => 100]);
        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => [$field]]);
    }

    /**
     * Returns the name of the testing field.
     *
     * @return string
     */
    abstract protected function getFieldName(): string;

    /**
     * @return void
     */
    public function test_field_has_wrong_operator()
    {
        $field = $this->getFieldName();

        $response = $this->makeRequest([$field . '[wrong_operator]' => 100]);
        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => [$field . '.wrong_operator']]);
    }

    /**
     * @return void
     */
    public function test_field_equal_operator_with_one_value()
    {
        $field = $this->getFieldName();
        $dataset = $this->getBaseDataset();

        $response = $this->makeRequest([$field . '[eq]' => $dataset[1]]);
        $response->assertStatus(200);
        $responseIds = $response->json('data.*.id');

        $expectedVacationIds = $this->items->filter(function ($item) use ($field, $dataset) {
            return $this->isEqualTo($item->$field, $dataset[1]);
        })->pluck('id')->toArray();
        $this->assertEquals($expectedVacationIds, $responseIds);

    }

    /**
     * Returns the dataset with the three items: min, middle and max.
     *
     * @return array
     */
    abstract protected function getBaseDataset(): array;

    /**
     * @return void
     */
    public function test_field_equal_operator_with_multiple_values()
    {
        $field = $this->getFieldName();
        $dataset = $this->getBaseDataset();

        $items = $this->makeVacations(2, [$field => $dataset[1]]);
        $this->items = $this->items->merge($items);

        $response = $this->makeRequest([$field . '[eq]' => $dataset[1]]);
        $response->assertStatus(200);
        $responseIds = $response->json('data.*.id');

        $expectedVacationIds = $this->items->filter(function ($item) use ($field, $dataset) {
            return $this->isEqualTo($item->$field, $dataset[1]);
        })->pluck('id')->toArray();
        $this->assertEquals($expectedVacationIds, $responseIds);

    }

    /**
     * @return void
     */
    public function test_field_greater_operator()
    {
        $field = $this->getFieldName();
        $dataset = $this->getBaseDataset();

        $response = $this->makeRequest([$field . '[gte]' => $this->increaseValue($dataset[1])]);
        $response->assertStatus(200);
        $responseIds = $response->json('data.*.id');

        $expectedVacationIds = $this->items->filter(function ($item) use ($field, $dataset) {
            return $this->isGreaterThanOrEqual($item->$field, $dataset[2]);
        })->pluck('id')->toArray();
        $this->assertEquals($expectedVacationIds, $responseIds);
    }

    /**
     * Increases the value of the field.
     *
     * @param $value
     *
     * @return mixed
     */
    abstract protected function increaseValue($value);

    /**
     * @return void
     */
    public function test_field_greater_operator_strictness()
    {
        $field = $this->getFieldName();
        $dataset = $this->getBaseDataset();

        $response = $this->makeRequest([$field . '[gte]' => $dataset[1]]);
        $response->assertStatus(200);
        $responseIds = $response->json('data.*.id');

        $expectedVacationIds = $this->items->filter(function ($item) use ($field, $dataset) {
            return $this->isGreaterThanOrEqual($item->$field, $dataset[1]);
        })->pluck('id')->toArray();
        $this->assertEquals($expectedVacationIds, $responseIds);

    }

    /**
     * @return void
     */
    public function test_field_less_operator()
    {
        $field = $this->getFieldName();
        $dataset = $this->getBaseDataset();

        $response = $this->makeRequest([$field . '[lte]' => $this->decreaseValue($dataset[2])]);
        $response->assertStatus(200);
        $responseIds = $response->json('data.*.id');

        $expectedVacationIds = $this->items->filter(function ($item) use ($field, $dataset) {
            return $this->isLessThanOrEqual($item->$field, $dataset[1]);
        })->pluck('id')->toArray();
        $this->assertEquals($expectedVacationIds, $responseIds);

    }

    /**
     * Decreases the value of the field.
     *
     * @param $value
     *
     * @return mixed
     */
    abstract protected function decreaseValue($value);

    /**
     * @return void
     */
    public function test_field_less_operator_strictness()
    {
        $field = $this->getFieldName();
        $dataset = $this->getBaseDataset();

        $response = $this->makeRequest([$field . '[lte]' => $dataset[1]]);
        $response->assertStatus(200);
        $responseIds = $response->json('data.*.id');

        $expectedVacationIds = $this->items->filter(function ($item) use ($field, $dataset) {
            return $this->isLessThanOrEqual($item->$field, $dataset[1]);
        })->pluck('id')->toArray();
        $this->assertEquals($expectedVacationIds, $responseIds);
    }

    /**
     * @return void
     */
    public function test_field_combination_eq_gte_lte()
    {
        $field = $this->getFieldName();
        $dataset = $this->getBaseDataset();

        $response = $this->makeRequest([$field . '[eq]' => $dataset[0], $field . '[gte]' => $dataset[1], $field . '[lte]' => $dataset[2]]);
        $response->assertStatus(200);
        $responseIds = $response->json('data.*.id');

        $expectedVacationIds = $this->items->filter(function ($item) use ($field, $dataset) {
            return $this->isEqualTo($item->$field, $dataset[0]);
        })->pluck('id')->toArray();
        $this->assertEquals($expectedVacationIds, $responseIds);
    }

    /**
     * @return void
     */
    public function test_field_combination_gte_lte()
    {
        $field = $this->getFieldName();
        $dataset = $this->getBaseDataset();

        $response = $this->makeRequest([$field . '[gte]' => $dataset[0], $field . '[lte]' => $dataset[1]]);
        $response->assertStatus(200);
        $responseIds = $response->json('data.*.id');

        $expectedVacationIds = $this->items->filter(function ($item) use ($field, $dataset) {
            return $this->isGreaterThanOrEqual($item->$field, $dataset[0]) && $this->isLessThanOrEqual($item->$field, $dataset[1]);
        })->pluck('id')->toArray();
        $this->assertEquals($expectedVacationIds, $responseIds);
    }

    /**
     * Determines if the provided value is greater than the "comparedTo" value.
     *
     * @param $value
     * @param $comparedTo
     *
     * @return bool
     */
    abstract protected function isGreaterThanOrEqual($value, $comparedTo): bool;

    /**
     * Determines if the provided value is less than the "comparedTo" value.
     *
     * @param $value
     * @param $comparedTo
     *
     * @return bool
     */
    abstract protected function isLessThanOrEqual($value, $comparedTo): bool;

    /**
     * Determines if the provided value is equal to the "comparedTo" value.
     *
     * @param $value
     * @param $comparedTo
     *
     * @return bool
     */
    abstract protected function isEqualTo($value, $comparedTo): bool;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->items = collect();

        $values = $this->getBaseDataset();
        $field = $this->getFieldName();

        foreach ($values as $value) {
            $item = $this->makeVacations(1, [
                $field => $this->parseDatasetValueToMake($value),
            ]);

            $this->items = $this->items->merge($item);
        }
    }

    /**
     * Parses the raw value before creating an entity.
     *
     * @param $value
     *
     * @return mixed
     */
    abstract protected function parseDatasetValueToMake($value);

    /**
     * @inheritDoc
     */
    protected function getRouteName(): string
    {
        return 'vacations.index';
    }

    /**
     * @inheritDoc
     */
    protected function getRouteMethod(): string
    {
        return 'GET';
    }
}
