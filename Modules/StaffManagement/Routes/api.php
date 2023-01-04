<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/staff', function (Request $request) {
    return $request->user();
});