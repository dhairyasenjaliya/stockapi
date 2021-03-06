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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Stock API

Route::post('getstock','stockmanager@show');
Route::post('findallstock','stockmanager@findallstock');
Route::post('searchstock','stockmanager@searchstock');
Route::post('addstock','stockmanager@add');
Route::post('favcounter','stockmanager@fav');
Route::get('getallstock','stockmanager@all');
Route::post('bestreturnstock','stockmanager@bestreturnstock');
Route::get('removeallstock','stockmanager@removeallstock');


// Sector API 

Route::get('getsectors','stockmanager@getsector');
Route::post('addsector','stockmanager@addsector');
Route::post('sectorwisesotock','stockmanager@sectorwisesotock');

Route::post('findsector','stockmanager@findsector');