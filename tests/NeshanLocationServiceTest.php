<?php

use App\Exceptions\ApiException;
use App\Services\LocationServices\NeshanLocationService;
use App\Services\LocationServices\Contracts\LocationServiceInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use http\Client;
use PHPUnit\Framework\TestCase;

class NeshanLocationServiceTest extends TestCase
{
    public function testNeshanLocationService()
    {
        $service = new NeshanLocationService();

        $this->assertInstanceOf(LocationServiceInterface::class, $service);
    }

    public function testCreateRequestValidSearchApi(): void
    {
        $service = new NeshanLocationService();

        $request = $service->createRequest('search', 'tehran', 3.333, 3.333);

        $this->assertArrayHasKey('term', $request['params']);
        $this->assertArrayHasKey('lat', $request['params']);
        $this->assertArrayHasKey('lng', $request['params']);

        $this->assertArrayHasKey('headers', $request);

        $this->assertEquals('https://api.neshan.org/v1/search', $request['url']);
        $this->assertEquals('get', $request['method']);
        $this->assertEquals('tehran', $request['params']['term']);
    }

}
