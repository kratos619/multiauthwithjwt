<?php

use Illuminate\Http\Request;

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

Route::post('/customer/register',"CustomerAuthController@customer_register");
Route::post('/customer/login',"CustomerAuthController@login");

Route::group(['prefix' => 'admin','middleware' => ['assign.guard:apiadmin','jwt.auth']],function ()
{
	Route::get('/demo','AdminController@demo');	
});
Route::group(['prefix' => 'customer','middleware' => ['assign.guard:apicustomer','jwt.auth']],function ()
{
	Route::post('me',"CustomerAuthController@me");
	Route::post('logout',"CustomerAuthController@logout");
});