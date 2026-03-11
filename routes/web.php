<?php

use Illuminate\Support\Facades\Route;

Route::any('/{view?}', function () {
    return view('app');
})->where('any', '.*');
