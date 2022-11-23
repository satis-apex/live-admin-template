<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/app-setting', function (Request $request) {
    return $request->user();
});