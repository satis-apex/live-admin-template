<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user-management', function (Request $request) {
    return $request->user();
});