<?php

namespace App\Services\LocationServices\Contracts;

interface LocationServiceInterface
{
    public function callApi(array $request);

    public function createRequest(string $api, string $term, float $lat, float $lng);

    public function searchMap(string $term, float $lat, float $lng);
}