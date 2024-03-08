<?php

use App\Controllers\LocationServiceController;
use App\Core\Routing\Route;
use App\Middlewares\LocationMiddleware;

Route::get('/api/v1/location', [LocationServiceController::class, 'index'], LocationMiddleware::class);