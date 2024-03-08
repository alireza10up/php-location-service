<?php

namespace App\Middlewares;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Middlewares\Contracts\MiddlewareInterface;
use App\Utils\Validators;

class LocationMiddleware implements MiddlewareInterface
{

    #[\Override] public function handle(Request $request, array $params = null)
    {
        $errors = Validators::validateLocation($params);
        if (!empty($errors)) {
            $response = Response::getInstance();
            $response->withStatusCode(422)->withJson(['status' => false, 'message' => 'validation error', 'errors' => $errors])->send();
        }
    }
}