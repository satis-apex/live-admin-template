<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/menu-management', function (Request $request) {
    return $request->user();
});