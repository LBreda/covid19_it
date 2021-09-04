<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'DataController@dashboard')->name('data.total');
Route::get('/region/download', 'DataController@downloadData')->name('data.total_download');
Route::get('/region/{region}/download', 'DataController@downloadData')->name('data.region_download');
Route::get('/region/{region}', 'DataController@dashboard')->name('data.region');
Route::get('offline', fn () => view('offline'));
