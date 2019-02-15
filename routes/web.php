<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lost', 'LAFController@lostList');
Route::get('/lost/{id}', 'LAFController@lost');

Route::get('/found', 'LAFController@lostList');
Route::get('/found/{id}', 'LAFController@lost');

Route::group(['middleware'=>'cookie'], function (){
    Route::post('/login', 'UserController@login');
    Route::post('/user/update', 'UserController@updateUserInfo');
    Route::get('/user/lost', 'UserController@getUserLost');
    Route::get('/user/found', 'UserController@getUserFound');

    Route::post('/submit/lost', 'LAFController@submitLost');
    Route::post('/submit/found', 'LAFController@submitFound');

    Route::post('/update/lost/{id}', 'LAFController@updateLost');
    Route::post('/update/found/{id}', 'LAFController@updateFound');

    Route::match(['get', 'post'], '/finish/lost/{id}', 'LAFController@finishLost');
    Route::match(['get', 'post'], '/finish/found/{id}', 'LAFController@finishFound');
});
