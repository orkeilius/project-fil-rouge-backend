<?php

use App\Http\Controllers\api\v1\AuctionApiController;
use App\Http\Controllers\api\v1\OfferApiController;
use App\Http\Controllers\api\v1\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

//User
Route::Get('users', [UserApiController::class, 'index']);
Route::Get('users/{id}', [UserApiController::class, 'show']);
Route::Post('users', [UserApiController::class, 'store']);
Route::Put('users/{id}', [UserApiController::class, 'update'])
    ->middleware('auth:api');
Route::Delete('users/{id}', [UserApiController::class, 'destroy'])
    ->middleware('auth:api');

Route::Get('users/{id}/auction', [UserApiController::class, 'getAuctions']);
Route::Get('users/{id}/offer', [UserApiController::class, 'getOffers']);

//Auction
Route::Get('auctions', [AuctionApiController::class, 'index']);
Route::Get('auctions/{id}', [AuctionApiController::class, 'show']);
Route::Post('auctions', [AuctionApiController::class, 'store'])
    ->middleware('auth:api');
Route::Put('auctions/{id}', [AuctionApiController::class, 'update'])
    ->middleware('auth:api');
Route::Delete('auctions/{id}', [AuctionApiController::class, 'destroy'])
    ->middleware('auth:api');

//Offer
Route::Get('offers', [OfferApiController::class, 'index']);
Route::Get('offers/{id}', [OfferApiController::class, 'show']);
Route::Post('offers', [OfferApiController::class, 'store'])
    ->middleware('auth:api');
Route::Put('offers/{id}', [OfferApiController::class, 'update'])
    ->middleware('auth:api');
//Route::Delete('auctions/{id}', [AuctionApiController::class, 'destroy'])
//    ->middleware('auth:api');

