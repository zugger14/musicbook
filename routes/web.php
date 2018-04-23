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

/*`````````````				All admin routes 		``````````````````````					*/

Route::group(['prefix' => 'admin'], function () {

	Route::post('/logout','Auth\AdminLoginController@adminLogout')->name('admin.logout');
	
	Route::group(['middleware' => ['guest:admin']], function () {

		Route::get('/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');
		Route::post('/login','Auth\AdminLoginController@login')->name('admin.login.submit');
	});
	
	Route::group(['middleware' => ['auth:admin']], function () {

		Route::get('/','AdminController@index')->name('admin.dashboard');

	});

});


/*`````````````		normal routes wihtout any authentication		``````````````````````	*/

Route::group(['middleware' => ['web']], function () {
	
	Route::get('/', 'PageController@index')->name('landing');
	Route::post('user/logout','Auth\LoginController@userLogout')->name('user.logout');
	
	// handles all routes for normal users as fans and artists login
	Auth::routes();

	
});


/*`````````````		All user( both artists and fans ) routes 		``````````````````````	*/


Route::group(['middleware' => ['auth', 'artist']], function () {

	Route::get('home/artist', 'PageController@artistHome')->name('artist.home');
	Route::get('upload', 'PageController@upload')->name('upload');
	Route::resource('songs','SongController');

});

Route::group(['middleware' => ['auth', 'fan']], function () {

	Route::get('home/fan', 'PageController@fanHome')->name('fan.home');

});







