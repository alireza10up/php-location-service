<?php

namespace App\Middlewares;

use App\Core\Http\Request;
use App\Middlewares\Contracts\MiddlewareInterface;

class LocationMiddleware implements MiddlewareInterface
{

    #[\Override] public function handle(Request $request)
    {
        dd($request);
    }
}