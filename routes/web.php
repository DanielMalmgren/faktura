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

//Auth::routes();

Route::get('/',                         'HomeController@index');
Route::post('/',                        'HomeController@index');
Route::get('logout',                    'HomeController@logout');

Route::get('/spec',                     'SpecController@index');
Route::get('/spec/listajax',            'SpecController@listajax');

Route::get('/asset',                    'AssetController@index');
Route::get('/asset/listajax',           'AssetController@listajax');
Route::get('/asset/ordermodal',         'AssetController@ordermodal');
Route::get('/asset/orderstatusmodal',   'AssetController@orderstatusmodal');
Route::delete('/asset/order',           'AssetController@cancelorder');
