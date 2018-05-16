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

/*`````````````		normal routes usable without any authentication		``````````````````````	*/

Route::group(['middleware' => ['web']], function () {

	Route::get('/', 'PageController@index')->name('landing');

	Route::post('user/logout','Auth\LoginController@userLogout')->name('user.logout');
	
	// handles all routes for normal users as fans and artists login
	Auth::routes();

	
});


/*`````````````				All admin usable routes 		``````````````````````					*/

Route::group(['prefix' => 'admin'], function () {
	
	//routes for nonauthenticated admins only..not available for authenticated admins
	Route::group(['middleware' => ['guest:admin']], function () {

		Route::get('/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');
		Route::post('/login','Auth\AdminLoginController@login')->name('admin.login.submit');

		//admin password reset routes
		Route::post('/password/email','Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
		Route::get('/password/reset','Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
		Route::post('/password/reset','Auth\AdminResetPasswordController@reset');
		Route::get('/password/reset/{token}','Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');
	});
	
	//routes for authenticated admin
	Route::group(['middleware' => ['auth:admin']], function () {

		Route::get('/','AdminController@index')->name('admin.dashboard');
		Route::post('/logout','Auth\AdminLoginController@adminLogout')->name('admin.logout');
	});

});

/*`````````````		 Fan and Artists usable common routes 		``````````````````````	*/


Route::group(['middleware' => ['auth:web']], function () {

	//profile routes
	Route::get('/profile/{slug}', 'ProfileController@index')->name('profile.show');
	Route::get('/profile/{slug}/edit', 'ProfileController@edit')->name('profile.edit');
	Route::put('/profile/update', 'ProfileController@update')->name('profile.update');

	//friendship routes
	Route::get('/check_relationship_status/{user_id}','FriendshipsController@checkStatus' );

	Route::get('/addfriend/{user_id}', 'FriendshipsController@addFriend' );

	Route::get('/acceptfriend/{user_id}', 'FriendshipsController@acceptFriend' );

	Route::get('/removefriend/{user_id}', 'FriendshipsController@removeFriend' );

	Route::get('/getpendingrequests', 'FriendshipsController@getPendingRequests' );

	Route::get('/removependingrequest/{user_id}', 'FriendshipsController@removePendingRequest' );



	
	/*Route::get('/accept', function() {
		return App\User::find(5)->acceptFriend(1);

	});

	Route::get('/friends', function() {
		return App\User::find(Auth::id())->friends();
	});

	Route::get('/pending', function() {
		return App\User::find(Auth::id())->pendingRequest();
	});

	Route::get('/ids', function() {
		return App\User::find(1)->friendsId();
	});

	Route::get('/is', function() {
		return App\User::find(1)->isFriendWith(7);
	});
	*/

	//notifications route
	Route::get('/notifications','PageController@showAllNotifications')->name('notifications');
	
	Route::get('/markAsRead', function() {
		Auth::user()->unreadNotifications->markAsRead();
	});


	//like and unlike routes
	Route::get('/auth_user_data', function() {
		return Auth::user();
	});

	Route::get('/like/{song_id}','LikeController@like' );

	Route::get('/unlike/{song_id}','LikeController@unLike' );


	//song routes for authenticated users
	Route::group(['prefix' => 'artist'], function () {

		Route::get('songs/buy','SongController@addToOrderList');
		Route::get('songs/{song_id}','SongController@getPrivateSong');
		Route::get('demos/{artist_id}','SongController@getPrivateSongDemo');
		Route::resource('songs','SongController');
	});

	Route::get('songfeeds','SongController@songFeeds');	//songs for users from all his friedns


});

/*`````````````		 only artists usable routes 		``````````````````````	*/

Route::group(['middleware' => ['auth:web', 'artist']], function () {

	Route::group(['prefix' => 'artist'], function () {

		Route::get('home', 'PageController@artistHome')->name('artist.home');
		Route::get('collections', 'PageController@collection')->name('artist.collections');

	});
});

/*`````````````		 only fans usable routes 		``````````````````````	*/

Route::group(['middleware' => ['auth:web', 'fan']], function () {

	Route::group(['prefix' => 'fan'], function () {
		Route::get('home', 'PageController@fanHome')->name('fan.home');

	});


	/* 
	*** paypal payment gateway routes 
	*/
	Route::group(['prefix' => 'payments'], function () {
		Route::get('/','PaypalController@index');
		Route::get('/show/{payment_id}','PaypalController@show');
		Route::get('with-credit-card','PaypalController@paywithCreditCard')->name('payment.creditcard');
		Route::get('with-paypal','PaypalController@paywithPaypal')->name('payment.paypal');

		Route::get('fails', function () {
		    // The user cancelled the payment
		    // Show appropriate view/message
		    return 'fail';
		});
		Route::get('success', function () {
		    // The user cancelled the payment
		    // Show appropriate view/message
		    return 'success';

		});


	});


	/* 
	*** esewa payment gateay routes 
	*/

	/*Route::get('failure', function () {
		    // The user cancelled the payment
		    // Show appropriate view/message
	});
	Route::get('success', function () {
	    // Find the order from the order id
	    $order = Order::find(request()->get('oid'));
	    // Check the payment by providing the order id, amount and the reference id
	    // Note: DONOT USE THE AMOUNT FROM THE REQUEST AS IT MIGHT HAVE BEEN ALTERED
	    if (Esewa::with(['id' => $order->id, 'amount' => $order->total_amount, 'reference_id' => request()->get('refId')])->isPaid()) {
	        // Update the order
	        // Show success message
	    }
	    // The payment was not completed. 
	    // Show Error Message
	});*/

});







