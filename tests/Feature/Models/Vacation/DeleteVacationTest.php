<?php

namespace Tests\Feature\Models\Vacation;

use App\Models\Vacation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Models\ModelTestsHelpersTrait;
use Tests\TestCase;

class DeleteVacationTest extends TestCase
{
    use RefreshDatabase, VacationTestHelpersTrait, ModelTestsHelpersTrait;

    /**
     * @return void
     */
    public function test_successful_vacation_delete()
    {
        $item = $this->makeVacations(1)->first();

        $response = $this->makeRequest([], [], ['vacation' => $item->id]);
        $response->assertStatus(204);

        $this->assertEmpty($response->getContent());
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
        $response = $this->makeRequest([], [], ['vacation' => $id]);
        $response->assertStatus(404);
    }

    /**
     * @inheritDoc
     */
    protected function getRouteName(): string
    {
        return 'vacations.destroy';
    }

    /**
     * @inheritDoc
     */
    protected function getRouteMethod(): string
    {
        return 'DELETE';
    }
}
