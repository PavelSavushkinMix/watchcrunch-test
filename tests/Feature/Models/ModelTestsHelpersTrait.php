<?php

namespace Tests\Feature\Models;

use Illuminate\Testing\TestResponse;

trait ModelTestsHelpersTrait
{
    /**
     * Makes a request to the URI based on the route name and the route's method.
     *
     * @param array $data
     * @param array $headers
     * @param array $routeParameters
     *
     * @return TestResponse
     */
    protected function makeRequest(array $data = [], array $headers = [], array $routeParameters = []): TestResponse
    {
        $method = strtoupper($this->getRouteMethod());
        $routeName = $this->getRouteName();

        if ($method === 'GET') {
            $routeParameters = $data;
            $data = [];
        }
        $uri = route($routeName, $routeParameters);

        return $this->json($method, $uri, $data, $headers);
    }

    /**
     * Returns the name of the route that is testing.
     *
     * @return string
     */
    abstract protected function getRouteMethod(): string;

    /**
     * Returns the name of the route that is testing.
     *
     * @return string
     */
    abstract protected function getRouteName(): string;
}
