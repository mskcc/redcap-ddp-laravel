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

Route::post('/metadata', 'Api\MetadataController@index')
    ->name('api.metadata.index');


Route::post('/data', 'Api\DataController@index')
    ->name('api.data.index');