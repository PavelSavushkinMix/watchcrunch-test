<?php

namespace Tests\Feature\Models\Vacation;

use App\Models\Vacation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Models\ModelTestsHelpersTrait;
use Tests\TestCase;

class ShowVacationTest extends TestCase
{
    use RefreshDatabase, VacationTestHelpersTrait, ModelTestsHelpersTrait;

    /**
     * @return void
     */
    public function test_successful_vacation_show()
    {
        $item = $this->makeVacations(1)->first();

        $response = $this->makeRequest(['vacation' => $item->id]);
        $response->assertStatus(200);

        $responseJson = $response->json('data');
        $this->assertEquals($item->start->format(Vacation::DATES_FORMAT), $responseJson['start']);
        $this->assertEquals($item->end->format(Vacation::DATES_FORMAT), $responseJson['end']);
        $this->assertEquals($item->price, $responseJson['price']);
        $this->assertNull($responseJson['deleted_at']);
    }

    /**
     * Returns the dataset with the invalid data for the request.
     *
     * @return array
     */
    public function invalidRequestDataset(): array
    {
        return [
            'string as an id of the entity' => ['wrong_id'],
            'negative value of the id' => [-1],
            'absent value of the id' => [123456],
        ];
    }

    /**
     * @return void
     *
     * @dataProvider invalidRequestDataset
     */
    public function test_invalid_request($id)
    {
        $response = $this->makeRequest(['vacation' => $id]);
        $response->assertStatus(404);
    }

    /**
     * @inheritDoc
     */
    protected function getRouteName(): string
    {
        return 'vacations.show';
    }

    /**
     * @inheritDoc
     */
    protected function getRouteMethod(): string
    {
        return 'GET';
    }
}
