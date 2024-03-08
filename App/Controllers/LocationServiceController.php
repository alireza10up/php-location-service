<?php

namespace App\Controllers;

use App\Controllers\Contracts\AbstractBaseController;
use App\Core\Http\Request;
use App\Exceptions\ApiException;
use App\Services\LocationServices\NeshanLocationService;
use App\Utils\Config;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Searches for locations using a provided term, latitude, and longitude.
 *
 * This endpoint utilizes the Neshan location API to search for locations based on the
 * provided parameters. It returns a JSON response with the search results.
 *
 * @param Request $request The HTTP request object.
 * @param string $term The search term to use for location lookup.
 * @param float $lat The latitude coordinate.
 * @param float $lng The longitude coordinate.
 * @return void
 * @throws Exception If an unexpected error occurs.
 * @throws ApiException If an error occurs during the API call.
 */
class LocationServiceController extends AbstractBaseController
{
    public function index(Request $request): void
    {
        try {
            $term = $request->term;
            $lng = $request->lng;
            $lat = $request->lat;

            $locationProvider = new NeshanLocationService(new Config());

            $result = $locationProvider->searchMap($term, $lat, $lng);

            $this->jsonResponse(array_merge(['status' => true], $result), 200);
        } catch (ApiException|GuzzleException $e) {

            $this->jsonResponse([
                'status' => false,
                'message' => $e->getMessage(),
                'apiStatusCode' => $e->getStatusCode()
            ], 400);

        } catch (Exception $e) {

            $this->jsonResponse([
                'status' => false,
                'message' => 'An unexpected error has occurred',
            ], 500);

        }
    }

}