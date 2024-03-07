<?php

use App\Core\Http\Request;
use App\Middlewares\Contract\MiddlewareInterface;

class LocationServiceMiddleware implements MiddlewareInterface
{

    #[\Override] public function handle(Request $request)
    {
        // TODO: Implement handle() method.
    }
}