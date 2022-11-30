<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user-role', function (Request $request) {
    return $request->user();
});