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

Route::get('/login', 'SessionController@index')->name('auth.login');
Route::post('/login', 'SessionController@login');
Route::get('/logout', 'SessionController@logout');

Route::group(['middleware' => ['web', 'pin']], function () {
    Route::get('/', 'UploadController@index')->name('home');
    Route::post('/store','UploadController@store');
});
