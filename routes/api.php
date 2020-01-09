<?php

use Illuminate\Http\Request;

// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// checkUser middleware for check if user already has taken discount
Route::post("/discounts", ['middleware' => 'checkUser','uses' => "DiscountsController@discounts",])->name('discounts');
