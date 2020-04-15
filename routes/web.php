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

Route::group(['middleware' => ['web']], function () {

    Route::get('/login', 'SessionController@index')->name('auth.login');
    Route::get('/logout', 'SessionController@logout');

    Route::middleware('throttle:30,1')->group(function () {
        Route::post('/login', 'SessionController@login');
    });

    Route::get('/share/{dir}', 'StorageController@index')->name('storage');
    Route::get('/archive/{dir}', 'StorageController@archive');

    Route::group(['middleware' => ['pin']], function () {
        Route::get('/', 'UploadController@index')->name('home');
        Route::post('/upload', 'UploadController@upload');
        Route::post('/store', 'UploadController@store');
        Route::post('/sendmail', 'UploadController@sendmail');
    });


});
