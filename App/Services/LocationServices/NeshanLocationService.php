<?php

namespace App\Services\LocationServices;

use App\Exceptions\ApiException;
use App\Services\LocationServices\Contracts\LocationServiceInterface;
use App\Utils\Config;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

class NeshanLocationService implements LocationServiceInterface
{
    private ?array $config;

    public function __construct()
    {
        $this->config = Config::get('servicesLocation');
    }

    /**
     * Creates a request for the Neshan API based on provided details.
     *
     * @param string $api Name of the Neshan API
     * @param string $term Search term
     * @param float|null $lat Latitude
     * @param float|null $lng Longitude
     * @return array Request details including URL, method, parameters, and headers
     */
    #[\Override] public function createRequest(string $api, string $term, ?float $lat = 0, ?float $lng = 0): array
    {
        $config = $this->config['neshan']['apis'][$api] ?? '';

        $url = $config['url'];
        $method = $config['method'];
        $params = [
            'term' => $term,
            'lat' => $lat,
            'lng' => $lng,
        ];
        $headers = $config['headers'];

        return [
            'url' => $url,
            'method' => $method,
            'params' => $params,
            'headers' => $headers,
        ];
    }

    /**
     * Searches the map using the Neshan "search" API.
     *
     * @param string $term Search term
     * @param float|null $lat Latitude
     * @param float|null $lng Longitude
     * @return array Response data from the Neshan API
     * @throws ApiException If an error occurs during API call
     * @throws GuzzleException If an unexpected exception occurs
     */
    #[\Override] public function searchMap(string $term, ?float $lat, ?float $lng): array
    {
        $request = $this->createRequest('search', $term, $lat, $lng);

        return $this->callApi($request);
    }

    /**
     * Makes a call to the Neshan API with a provided request.
     *
     * @param array $request Request details including URL, method, parameters, and headers
     * @return array Decoded response data from the Neshan API
     * @throws ApiException If an API error occurs
     * @throws RuntimeException|GuzzleException If an unexpected exception occurs
     */
    #[\Override] public function callApi(array $request)
    {
        $config = env("PRODUCTION", true) ? [] : [
            'curl' => [
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            ]
        ];

        $httpClient = new \GuzzleHttp\Client($config);

        try {
            $response = $httpClient->request(
                $request['method'],
                $request['url'],
                [
                    'query' => $request['params'],
                    'headers' => $request['headers'],
                ]
            );

            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseData = json_decode($response->getBody()->getContents(), true);
            throw new ApiException($responseData['message'], $responseData['code'] ?? 500);
        } catch (Exception $e) {
            throw new RuntimeException('An unexpected error has occurred' . $e->getMessage());
        }
    }
}