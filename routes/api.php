<?php

use App\Core\Routing\Route;

Route::get('/api/v1/location/{term}/{lat}/{lng}', 'LocationServiceController@index' , 'LocationMiddleware');