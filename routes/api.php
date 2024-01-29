<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});
//Route::get('/login', 'UserController@loginSubmit');
Route::get('/login-submit','v1\UserController@loginSubmit');
Route::get('/faq', 'v1\FaqController@faq');
//Route::get('/faq-test', 'v1\FaqController@faqtest');
Route::post('/seller-login', 'v1\Vendor\VendorController@authentication');
Route::post('/seller-signup', 'v1\Vendor\VendorController@create');
Route::get('/getcardata', 'v1\CarController@getCarData');
Route::get('/adsList', 'v1\CarController@index');
Route::get('/car-filters', 'v1\CarController@filters');
Route::get('ads-detail', 'v1\CarController@details');
Route::get('getcar_model', 'v1\CarController@getcarModel');
Route::get('get-categories', 'v1\CarController@fetchCategory');
Route::get('getcar-make', 'v1\CarController@getcarMake');
Route::get('get-fueltype', 'v1\CarController@getFuelType');
Route::get('get-transtype', 'v1\CarController@getTransmissionType');
Route::get('get-bodytype', 'v1\CarController@getBodyType');
Route::get('get-carcolour', 'v1\CarController@getColorType');
Route::get('get-caryears', 'v1\CarController@getCarYear');
Route::get('get-location', 'v1\CarController@getLocation');
Route::get('get-prices', 'v1\CarController@getPrices');


