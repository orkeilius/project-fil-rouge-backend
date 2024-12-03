<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserApiController;
use App\Http\Controllers\api\v1\AuctionApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::resource('users', UserApiController::class);
Route::resource('auctions', AuctionApiController::class);
