<?php

namespace App\Middlewares\Contracts;
use App\Core\Http\Request;

interface MiddlewareInterface
{
    public function handle(Request $request);
}