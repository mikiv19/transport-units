<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-enum', function() {
    return App\Enums\TransportUnitType::TRUCK->value;
});