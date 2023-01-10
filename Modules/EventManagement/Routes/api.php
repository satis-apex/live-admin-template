<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/announcement', function (Request $request) {
    return $request->user();
});