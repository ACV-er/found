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
    use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
        return view('welcome');
    });


    Route::group(['middleware' => 'cookie'], function () {
        Route::post('/login', 'UserController@login');

        Route::get('/laf', 'LAFController@postList');
        Route::get('/laf/{id}', 'LAFController@post')->where(["id"=>'[0-9]+']);
        Route::post('/search', 'LAFController@search');

        Route::group(['middleware' => 'loginCheck'], function () {
            Route::post('/user/update', 'UserController@updateUserInfo');

            Route::get('/user/laf', 'UserController@getUserPost');

            Route::get('/user/info', 'UserController@getUserInfo');
            Route::post('/user/avatar', 'UserController@saveAvatar');

            Route::match(['get', 'post'], '/mark/{id}', 'LAFController@markPost')->where(["id"=>'[0-9]+']);
        });

        Route::group(['middleware' => 'checkInfo'], function () {

            Route::post('/submit', 'LAFController@submitPost')->middleware('deduplicate');

            Route::post('/update/{id}', 'LAFController@updatePost')->where(["id"=>'[0-9]+']);

            Route::match(['get', 'post'], '/finish/{id}', 'LAFController@finishPost')->where(["id"=>'[0-9]+']);
        });
    });


    Route::get('/manager/login', function (){
        return view('manager.login');
    });
    Route::post('/manager/login', 'ManagerController@login');
    Route::group(['middleware' => 'managerCheck'], function (){
        Route::get('/manager/post', function (){
            return view('manager.laf');
        });

        Route::get('/manager/user', function (){
            return view('manager.user');
        });

        Route::get('/manager/manager', function (){
            return view('manager.manager');
        });

        Route::get('/manager/other', function (){
            return view('manager.other');
        });

        Route::get('/user/search', 'UserController@searchUser');
        Route::get('/user/laf/{id}', 'UserController@manager_getUserPost');
//        Route::get('/user/ban/{id}');

        Route::post('/manager/add', 'ManagerController@managerAdd');
        Route::get('/manager/delete/{id}', 'ManagerController@managerDelete');
        Route::get('/manager/list', 'ManagerController@managerList');
        Route::post('manager/update/{id}', 'ManagerController@managerUpdate');

        Route::post('/manager/post/update/{id}', 'ManagerController@updatePost');
        Route::get('/manager/post/delete/{id}', 'ManagerController@deletePost');
    });

