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


Route::group(['middleware'=>'cookie'], function (){
    Route::post('/login', 'UserController@login');
//    Route::get('/logintest', 'UserController@logintest');
//    Route::get('/lost', 'LAFController@lostList');
//    Route::get('/lost/{id}', 'LAFController@lost');
//
//    Route::get('/found', 'LAFController@lostList');
//    Route::get('/found/{id}', 'LAFController@lost');

    Route::get('/laf', 'LAFController@postList');
    Route::get('/laf/{id}', 'LAFController@post');

    Route::group(['middleware'=>'loginCheck'], function () {
        Route::post('/user/update', 'UserController@updateUserInfo');
//        Route::get('/user/lost', 'UserController@getUserLost');
//        Route::get('/user/found', 'UserController@getUserFound');

//        Route::get('/user/laf', 'UserController@getUserPost');
        Route::get('/user/laf', 'UserController@getUserPost');

        Route::get('/user/info', 'UserController@getUserInfo');
        Route::post('/user/avatar', 'UserController@saveAvatar');

//        Route::match(['get', 'post'], '/mark/lost/{id}', 'LAFController@markLost');
//        Route::match(['get', 'post'], '/mark/found/{id}', 'LAFController@markFound');
        Route::match(['get', 'post'], '/mark/{id}', 'LAFController@markPost');
    });

    Route::group(['middleware'=>'checkInfo'], function () {
//        Route::post('/submit/lost', 'LAFController@submitLost');
//        Route::post('/submit/found', 'LAFController@submitFound');
        Route::post('/submit', 'LAFController@submitPost');

//        Route::post('/update/lost/{id}', 'LAFController@updateLost');
//        Route::post('/update/found/{id}', 'LAFController@updateFound');
        Route::post('/update/{id}', 'LAFController@updatePost');

//        Route::match(['get', 'post'], '/finish/lost/{id}', 'LAFController@finishLost');
//        Route::match(['get', 'post'], '/finish/found/{id}', 'LAFController@finishFound');
        Route::match(['get', 'post'], '/finish/{id}', 'LAFController@finishPost');
    });
});
