<?php

namespace App\Services\LocationServices;

use App\Exceptions\ApiException;
use App\Services\LocationServices\Contracts\LocationServiceInterface;
use App\Utils\Config;

class NeshanLocationService implements LocationServiceInterface
{
    private ?array $config;

    public function __construct(Config $config)
    {
        $this->config = $config::get('servicesLocation');
    }

    #[\Override] public function createRequest(string $api, string $term, float $lat, float $lng): array
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

    #[\Override] public function searchMap(string $term, float $lat, float $lng): array
    {
        $request = $this->createRequest('search', $term, $lat, $lng);

        return $this->callApi($request);
    }

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
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseData = json_decode($response->getBody()->getContents(), true);
            throw new ApiException($responseData['message'], $responseData['code']);
        } catch (\Exception $e) {
            throw new \RuntimeException('خطای غیر منتظره رخ داده است: ' . $e->getMessage());
        }
    }
}