<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/employee', fn (Request $request) => $request->user());
