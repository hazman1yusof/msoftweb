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

Route::get('/','HomeController@index')->name('home');
Route::get('/login','SessionController@create')->name('login');
Route::post('/login','SessionController@store');
Route::get('/logout','SessionController@destroy')->name('logout');

Route::get('/religion',function(){
	return view('setup.religion.religion');
});

