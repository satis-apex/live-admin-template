<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/menu-link', function (Request $request) {
    return $request->user();
});