<?php

namespace App\Controllers;

use App\Controllers\Contracts\AbstractBaseController;
use App\Core\Http\Request;
use App\Exceptions\ApiException;
use App\Services\LocationServices\NeshanLocationService;
use App\Utils\Config;

class LocationServiceController extends AbstractBaseController
{
    public function index(Request $request, $term, $lat, $lng): void
    {
        try {
            $locationProvider = new NeshanLocationService(new Config());

            $result = $locationProvider->searchMap($term, $lat, $lng);

            $this->jsonResponse(array_merge(['status' => true], $result), 200);
        } catch (ApiException $e) {

            $this->jsonResponse([
                'status' => false,
                'message' => $e->getMessage(),
                'apiStatusCode' => $e->getStatusCode()
            ], 400);

        } catch (\Exception $e) {
            $this->jsonResponse([
                'status' => false,
                'message' => 'An unexpected error has occurred',
            ], 500);

        }
    }

}