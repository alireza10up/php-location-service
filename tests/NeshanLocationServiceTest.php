<?php

use App\Exceptions\ApiException;
use App\Services\LocationServices\NeshanLocationService;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class NeshanLocationServiceTest extends TestCase
{
    private NeshanLocationService $neshanLocationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->neshanLocationService = new NeshanLocationService(new \App\Utils\Config());
    }

    public function testCreateRequest(): void
    {
        $api = 'search';
        $term = 'mashhad';
        $lat = 35.0000;
        $lng = 51.2828;

        $request = $this->neshanLocationService->createRequest($api, $term, $lat, $lng);

        $this->assertArrayHasKey('url', $request);
        $this->assertArrayHasKey('method', $request);
        $this->assertArrayHasKey('params', $request);
        $this->assertArrayHasKey('headers', $request);

        $this->assertEquals('https://api.neshan.org/v2/search', $request['url']);
        $this->assertEquals('GET', $request['method']);
        $this->assertEquals(['term' => $term, 'lat' => $lat, 'lng' => $lng], $request['params']);

        $this->assertArrayHasKey('Authorization', $request['headers']);
        $this->assertArrayHasKey('Content-Type', $request['headers']);
    }

    public function testSearchMap(): void
    {
        $term = 'mashhad';
        $lat = 35.0000;
        $lng = 51.2828;

        $requestResult = [];

        $this->neshanLocationService = $this->getMockBuilder(NeshanLocationService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createRequest'])
            ->getMock();

        $this->neshanLocationService->expects($this->once())
            ->method('createRequest')
            ->with('search', $term, $lat, $lng)
            ->willReturn($requestResult);

        $this->assertEquals($requestResult, $this->neshanLocationService->searchMap($term, $lat, $lng));
    }

    public function testCallApi(): void
    {
        $httpClientMock = $this->getMockBuilder(\GuzzleHttp\Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $url = 'https://api.neshan.org/v2/search';
        $method = 'GET';
        $params = ['term' => 'restaurant', 'lat' => 35.6892, 'lng' => 51.3890];
        $headers = ['Authorization' => 'Bearer YOUR_API_KEY', 'Content-Type' => 'application/json'];

        $request = ['url' => $url, 'method' => $method, 'params' => $params, 'headers' => $headers];
        $responseBody = '{"results": [{"name": "Restaurant A", "lat": 35.0000, "lng": 51.2828}]}';
        $responseStatusCode = 200;

        $responseMock = $this->getMockBuilder(\Psr\Http\Message\ResponseInterface::class)->getMock();
        $responseMock->method('getBody')->willReturnSelf();
        $responseMock->method('getContents')->willReturn($responseBody);
        $responseMock->method('getStatusCode')->willReturn($responseStatusCode);

        $httpClientMock->expects($this->once())
            ->method('request')
            ->with($method, $url, ['query' => $params, 'headers' => $headers])
            ->willReturn($responseMock);

        $this->neshanLocationService = $this->getMockBuilder(NeshanLocationService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createRequest'])
            ->getMock();

        $this->neshanLocationService->expects($this->once())
            ->method('createRequest')
            ->willReturn($request);

        $this->assertEquals(
            json_decode($responseBody, true),
            $this->neshanLocationService->callApi($request)
        );
    }
}
