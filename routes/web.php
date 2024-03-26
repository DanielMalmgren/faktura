<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/asset/subassets',          'AssetController@subassets');
Route::post('/asset/dontreplace',       'AssetController@dontreplace');
Route::delete('/asset/order',           'AssetController@cancelorder');

Route::get('/label/{serial}',           'LabelController@single')->withoutMiddleware(['auth']);
Route::get('/label',                    'LabelController@index')->withoutMiddleware(['auth']);
