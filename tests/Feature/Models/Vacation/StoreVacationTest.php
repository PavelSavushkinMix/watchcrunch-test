<?php

namespace Tests\Feature\Models\Vacation;

use App\Models\Vacation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Models\ModelTestsHelpersTrait;
use Tests\TestCase;

class StoreVacationTest extends TestCase
{
    use RefreshDatabase, VacationTestHelpersTrait, ModelTestsHelpersTrait;

    /**
     * @return void
     */
    public function test_successful_vacation_store()
    {
        $response = $this->makeRequest([
            'start' => '2023-01-01T15:47:23',
            'end' => '2023-02-01T15:47:23',
            'price' => 26.57,
        ]);
        $response->assertStatus(201);

        $responseJson = $response->json('data');
        $this->assertEquals('2023-01-01T15:47:23', $responseJson['start']);
        $this->assertEquals('2023-02-01T15:47:23', $responseJson['end']);
        $this->assertEquals(26.57, $responseJson['price']);
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
            'totally same datetime values' => [
                [
                    'start' => '2023-01-01T15:47:23',
                    'end' => '2023-01-01T15:47:23',
                    'price' => 26.57,
                ],
                'end',
            ],
            'end after today' => [
                [
                    'start' => '2023-01-01T15:47:23',
                    'end' => now()->addDay()->format(Vacation::DATES_FORMAT),
                    'price' => 26.57,
                ],
                'end',
            ],
            'start after end' => [
                [
                    'start' => '2023-01-01T15:47:23',
                    'end' => '2022-01-01T15:47:23',
                    'price' => 26.57,
                ],
                'end',
            ],
            'missed start field' => [
                [
                    'end' => '2022-01-01T15:47:23',
                    'price' => 26.57,
                ],
                'start',
            ],
            'missed end field' => [
                [
                    'start' => '2022-01-01T15:47:23',
                    'price' => 26.57,
                ],
                'end',
            ],
            'missed price field' => [
                [
                    'start' => '2022-01-01T15:47:23',
                    'end' => '2022-01-01T15:47:23',
                ],
                'price',
            ],
            'nullable price field' => [
                [
                    'start' => '2022-01-01T15:47:23',
                    'end' => '2022-01-01T15:47:23',
                    'price' => null,
                ],
                'price',
            ],
            'string price field' => [
                [
                    'start' => '2022-01-01T15:47:23',
                    'end' => '2022-01-01T15:47:23',
                    'price' => 'wrong price',
                ],
                'price',
            ],
            'wrong start format' => [
                [
                    'start' => '2022-01-01 15:47:23',
                    'end' => '2022-01-01T15:47:23',
                    'price' => 26.57,
                ],
                'start',
            ],
            'wrong end format' => [
                [
                    'start' => '2022-01-01T15:47:23',
                    'end' => '2022-01-01 15:47:23',
                    'price' => 26.57,
                ],
                'end',
            ],
            'wrong start value' => [
                [
                    'start' => '2022-01-01T27:47:23',
                    'end' => '2022-01-01T15:47:23',
                    'price' => 26.57,
                ],
                'start',
            ],
            'wrong end value' => [
                [
                    'start' => '2022-01-01T15:47:23',
                    'end' => '2022-01-01T27:47:23',
                    'price' => 26.57,
                ],
                'end',
            ],
            'nullable start value' => [
                [
                    'start' => null,
                    'end' => '2022-01-01T27:47:23',
                    'price' => 26.57,
                ],
                'end',
            ],
            'nullable end value' => [
                [
                    'start' => '2022-01-01T27:47:23',
                    'end' => null,
                    'price' => 26.57,
                ],
                'end',
            ],
        ];
    }

    /**
     * @param array $data
     * @param string $errorField
     *
     * @return void
     *
     * @dataProvider invalidRequestDataset
     */
    public function test_invalid_request(array $data, string $errorField)
    {
        $response = $this->makeRequest($data);
        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => [$errorField]]);
    }

    /**
     * @inheritDoc
     */
    protected function getRouteName(): string
    {
        return 'vacations.store';
    }

    /**
     * @inheritDoc
     */
    protected function getRouteMethod(): string
    {
        return 'POST';
    }
}
