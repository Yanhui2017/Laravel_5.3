<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    echo time_format('2015-12-12');
    return view('welcome');
});

Route::get('/m','IndexController@index');
Route::get('/m/pay','IndexController@pay');



Route::group(['middleware' => ['log']], function () {
    Route::get('mm','IndexController@index');
});
//
//Route::group(['middleware' => 'web'], function () {
//    Route::auth();
//
//    Route::get('/home', 'HomeController@index');
//});
//
//Route::get('/self/getIndex','SelfController@getIndex');
//
////RESTFUL
//Route::resource('self', 'SelfController');
//Route::resource('photo', 'SelfController',
//    ['only' => ['index', 'show']]);