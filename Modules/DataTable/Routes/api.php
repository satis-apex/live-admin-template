<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/data-table', function (Request $request) {
    return $request->user();
});