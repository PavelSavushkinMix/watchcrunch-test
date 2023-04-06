<?php

namespace Tests\Feature\Models\Vacation;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Models\ModelTestsHelpersTrait;
use Tests\TestCase;

class FetchVacationsTest extends TestCase
{
    use RefreshDatabase, VacationTestHelpersTrait, ModelTestsHelpersTrait;

    /**
     * @return void
     */
    public function test_successful_fetch_one_vacation()
    {
        $vacationIds = $this->makeVacations(1)->pluck('id')->toArray();

        $response = $this->makeRequest();
        $response->assertStatus(200);

        $responseIds = $response->json('data.*.id');
        $this->assertEquals($vacationIds, $responseIds);
    }

    /**
     * @return void
     */
    public function test_successful_fetch_multiple_vacations()
    {
        $vacationIds = $this->makeVacations(2)->pluck('id')->toArray();

        $response = $this->makeRequest();
        $response->assertStatus(200);

        $responseIds = $response->json('data.*.id');
        $this->assertEquals($vacationIds, $responseIds);
    }

    /**
     * @return void
     */
    public function test_pagination_offset_zero()
    {
        $perPage = 5;
        $vacationIds = $this->makeVacations(10)->take($perPage)->pluck('id')->toArray();

        $response = $this->makeRequest(['limit' => $perPage, 'offset' => 0]);
        $response->assertStatus(200);

        $responseIds = $response->json('data.*.id');
        $this->assertEquals($vacationIds, $responseIds);
    }

    /**
     * @return void
     */
    public function test_pagination_offset_one()
    {
        $perPage = 5;
        $vacationIds = $this->makeVacations(10)->take($perPage)->pluck('id')->toArray();

        $response = $this->makeRequest(['limit' => $perPage, 'offset' => 1]);
        $response->assertStatus(200);

        $responseIds = $response->json('data.*.id');
        $this->assertEquals($vacationIds, $responseIds);
    }

    /**
     * @return void
     */
    public function test_pagination_offset_two()
    {
        $perPage = 5;
        $vacationIds = $this->makeVacations(10)->skip($perPage)->take($perPage)->pluck('id')->toArray();

        $response = $this->makeRequest(['limit' => $perPage, 'offset' => 2]);
        $response->assertStatus(200);

        $responseIds = $response->json('data.*.id');
        $this->assertEquals($vacationIds, $responseIds);
    }

    /**
     * @return void
     */
    public function test_pagination_limit()
    {
        $perPage = 7;
        $vacationIds = $this->makeVacations(10)->take($perPage)->pluck('id')->toArray();

        $response = $this->makeRequest(['limit' => $perPage]);
        $response->assertStatus(200);

        $responseIds = $response->json('data.*.id');
        $this->assertEquals(count($vacationIds), count($responseIds));
    }

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
