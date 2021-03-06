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

Route::get('/', 'DataController@data')->name('api:total');
Route::get('/region/{region}', 'DataController@data')->name('api:region');
Route::get('/regions/incidence', 'DataController@regionalIncidence')->name('api:regions.incidence');

Route::get('/immuni/downloads', 'DataController@immuniDownloadsData')->name('api:immuni_downloads_total');
