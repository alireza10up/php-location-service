<?php

namespace App\Controllers;

use App\Controllers\Contracts\AbstractBaseController;
use App\Core\Http\Request;

class LocationServiceController extends AbstractBaseController
{
    public function index(Request $request): void
    {
        $config = env("PRODUCTION", true) ? [] : [
            'curl' => [
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            ]
        ];

        $client = new \GuzzleHttp\Client($config);

        $response = $client->request('get', 'https://alireza10up.ir/');

        $this->jsonResponse(['status' => true, 'method' => $request->params(), 'message' => 'success response'], 201);
    }
}