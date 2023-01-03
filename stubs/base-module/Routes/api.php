<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/{Controller-}', function (Request $request) {
    return $request->user();
});