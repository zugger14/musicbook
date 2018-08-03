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

	Route::get('/', 'PageController@index')->name('landing')->middleware('guest:admin','guest');
	Route::post('user/logout','Auth\LoginController@userLogout')->name('user.logout');

	// handles all auth routes for normal users which are fans and artists
	Auth::routes();
	Route::get('user/verify/{token}','Auth\RegisterController@verify')->name('user.verify');

	Route::get('/getprofile/{slug}', 'ProfileController@getProfile');//vuejs call
	Route::post('/changeprofilepic', 'ProfileController@updateProfilePic');
	Route::put('/profile/update', 'ProfileController@update')->name('profile.update');

	Route::get('/topsongs','SongController@getMostPlayedSongs');	
	Route::get('/recentsongs','SongController@getMostRecentSongs');


	//userpublic song
	/*
		Route::get('getusersongs/{user_id}','SongController@getUserSongs');
		Route::get('getmostplayedusersongs/{user_id}','SongController@getMostPlayedUserSongs');
	*/
	//demosong view
	/*	Route::get('demos/{artist_id}','SongController@getPrivateSongDemo');
		Route::get('getmostsoldusersongs/{user_id}','SongController@getMostSoldUserSongs');
	*/


	
});


/*`````````````				All admin usable routes 		``````````````````````					*/

Route::group(['prefix' => 'admin'], function () {
	
	//routes for nonauthenticated admins only..not available for authenticated admins
	Route::group(['middleware' => ['guest:admin']], function () {

		Route::get('/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');
		Route::post('/login','Auth\AdminLoginController@login')->name('admin.login.submit');

		//admin password reset routes
		Route::get('/password/reset','Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
		Route::post('/password/email','Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
		Route::get('/password/reset/{token}','Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');
		Route::post('/password/reset','Auth\AdminResetPasswordController@reset');
	});
	
	//routes for authenticated admin
	Route::group(['middleware' => ['auth:admin']], function () {

		Route::get('/','AdminController@index')->name('admin.dashboard');
		Route::post('/logout','Auth\AdminLoginController@adminLogout')->name('admin.logout');

		//admin navigation routes
		Route::get('/users', 'UserController@index')->name('users.index');
		Route::get('/deletedusers', 'UserController@deletedUsers')->name('users.deletedusers');
		Route::get('/createuser', 'UserController@create')->name('users.create');
		Route::post('/adduser', 'UserController@store')->name('users.store');
		Route::get('/deleteuser/{slug}', 'UserController@destroy')->name('users.destroy');
		Route::get('/userprofile/{slug}', 'UserController@profile')->name('users.profile');

		//Route::get('/copyrights', 'SongController@copyrights')->name('songs.copyrights');


		Route::get('/songs', 'SongController@index')->name('songs.all');
		Route::get('/deletedsongs', 'SongController@deletedSongs')->name('songs.deleted');
		Route::get('/artistsongs','SongController@artistSongs')->name('song.artists');
		Route::get('/fansongs','SongController@fanSongs')->name('song.fans');

		Route::get('/playlists', 'PlaylistController@index')->name('playlists.index');
		Route::get('/deletedplaylists', 'PlaylistController@deletedPlaylists')->name('playlists.deleted');

		Route::get('/liveevents', 'LiveEventController@allLiveEvents')->name('liveevents.allLiveEvents');
		Route::get('/notes', 'NoteController@allNotes')->name('notes.allNotes');
		Route::get('/reports', 'AdminController@report')->name('admin.reports');




		Route::resource('tags', 'TagController', ['except' => ['create']]);

		

	});

});

/*`````````````		 Fan and Artists usable common routes 		``````````````````````	*/


Route::group(['middleware' => ['auth:web' OR 'auth:admin']], function () {

	//chat routes

	//private message routes
	Route::get('/inbox','PageController@inbox')->name('inbox');
	
	Route::get('get-private-message-notifications','PrivateMessageController@getUserNotifications');
	Route::post('get-private-messages','PrivateMessageController@getPrivateMessages');
	Route::get('get-private-message-by-id/{message_id}','PrivateMessageController@getPrivateMessageById');
	Route::post('get-private-messages-sent','PrivateMessageController@getPrivateMessagesSent');
	Route::post('/send-private-message','PrivateMessageController@sendPrivateMessage');
	Route::get('users-list', function(){
		//return App\User::all()->except(Auth::id()); all users except autenticated
		return Auth::user()->friends();
	});


	//profile routes
	Route::get('/profile/{slug}', 'ProfileController@index')->name('profile.show');//laravel reurn view call
	Route::get('/getprofile/{slug}', 'ProfileController@getProfile');//vuejs call
	Route::post('/changeprofilepic', 'ProfileController@updateProfilePic');
	Route::get('/profile/{slug}/edit', 'ProfileController@edit')->name('profile.edit');
	Route::put('/profile/update', 'ProfileController@update')->name('profile.update');


	//friendship routes
	Route::get('/check_relationship_status/{user_id}','FriendshipsController@checkStatus' );

	Route::get('/addfriend/{user_id}', 'FriendshipsController@addFriend' );

	Route::get('/acceptfriend/{user_id}', 'FriendshipsController@acceptFriend' );

	Route::get('/removefriend/{user_id}', 'FriendshipsController@removeFriend' );

	Route::get('/getpendingrequests', 'FriendshipsController@getPendingRequests' );

	Route::get('/removependingrequest/{user_id}', 'FriendshipsController@removePendingRequest' );

	Route::get('/getfriends/{user_id}', 'FriendshipsController@getFriends' );


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

	//share routes
	Route::get('/share/{song_id}','ShareController@share' );
	Route::get('/unshare/{song_id}','ShareController@unshare' );

	//comment routes
	Route::post('/comment', 'CommentController@store')->name('comment.store');
	Route::get('/comment/{song_id}', 'CommentController@index')->name('comment.index');
	Route::delete('/comment/{comment_id}', 'CommentController@destroy')->name('comment.destroy');
	Route::put('/comment/{comment_id}', 'CommentController@update')->name('comment.update');



	//song routes for authenticated users
	Route::group(['prefix' => 'artist'], function () {

		Route::post('songs/buy','SongController@addToOrderList');
		Route::get('songs/{song_id}','SongController@getPrivateSong');
		Route::get('demos/{artist_id}','SongController@getPrivateSongDemo');
		Route::get('getmostsoldusersongs/{user_id}','SongController@getMostSoldUserSongs');

		Route::get('songs/download/{payment_id}','SongController@viewDownSong')->name('songs.download');

		Route::resource('songs','SongController');
	});

	Route::get('getusersongs/{user_id}','SongController@getUserSongs');
	Route::get('getmostplayedusersongs/{user_id}','SongController@getMostPlayedUserSongs');
	Route::get('getmostsoldusersongs/{user_id}','SongController@getMostSoldUserSongs');

	Route::get('getpublicsong/{song_id}','SongController@getPublicSong');
	Route::get('songfeeds','SongController@songFeeds');	//songs for users from all his friends
	//Route::get('/topsongs','SongController@getMostPlayedSongs');	
	//Route::get('/recentsongs','SongController@getMostRecentSongs');
	Route::get('/get-purchased-songs','SongController@getPurchasedSongs');

	
	Route::get('songs/liked/{user_id}','SongController@showLikedSongPage')->name('songs.liked');
	Route::get('/getLikedSongs/{user_id}','SongController@getLikedSongs');
	Route::get('/getSharedSongs/{user_id}','SongController@getSharedSongs');
	Route::get('/addSongPlayedTime/{song_id}','SongController@addSongPlayedTime');
	Route::post('/gettagids', 'TagController@getTagIds');



	//page routes (change to song routes)
	Route::get('tracks', 'PageController@tracks')->name('artist.tracks');//change tracks to songs maybe so put on song controller later


	//search routes
	Route::get('searchusers/{query_string}','PageController@searchUsers');

	//playlist routes
	Route::post('/playlist/', 'PlaylistController@store')->name('playlist.store');
	Route::post('/playlist/addsong', 'PlaylistController@addSongToPlaylist')->name('playlist.addsong');
	Route::get('/playlist/removesong/{s_id}/{p_id}', 'PlaylistController@removeSong')->name('playlist.removesong');
	Route::get('/playlist/getplaylist/{user_id}', 'PlaylistController@getPlaylist')->name('playlist.getplaylist');
	Route::get('/playlist/{user_id}', 'PlaylistController@userIndex')->name('artists.playlist');
	Route::get('/playlist/songs/{user_id}', 'PlaylistController@getPlaylistSongs')->name('playlist.songs');
	Route::delete('/playlist/{playlist_id}', 'PlaylistController@destroy')->name('playlist.songs');
	Route::put('/playlist/{playlist_id}', 'PlaylistController@update')->name('playlist.update');
	Route::post('/playlist/multi', 'PlaylistController@multiStore')->name('playlist.multistore');

	//Album routes
	//Route::post('/album/', 'AlbumController@store')->name('album.store');


	//song notes routes
	Route::get('/getnotes/{user_id}', 'NoteController@getNotes');
	Route::get('/notes/{user_id}', 'NoteController@index')->name('artist.notes');
	Route::post('/notes/', 'NoteController@store')->name('notes.store');
	Route::put('/notes/{user_id}', 'NoteController@update')->name('notes.update');
	Route::delete('/notes/{user_id}', 'NoteController@destroy')->name('notes.destroy');


	//video route
	Route::get('/live', 'LiveEventController@getToken')->name('event.auth-token');
	Route::get('/live-events', 'LiveEventController@index')->name('event.index');
	Route::get('/google-login', 'LiveEventController@login')->name('event.login');

	Route::post('/create-event/', 'LiveEventController@createLiveEvent')->name('event.store');
	Route::post('/edit-event/{event_id}', 'LiveEventController@updateEvent')->name('event.update');
	Route::delete('/delete-event/{event_id}', 'LiveEventController@deleteEvent')->name('event.delete');

	Route::get('/test-event/{event_id}', 'LiveEventController@testEventLiveStream')->name('event.test');
	Route::get('/start-event/{event_id}', 'LiveEventController@startEventLiveStream')->name('event.start');
	Route::get('/stop-event/{event_id}', 'LiveEventController@stopEventLiveStream')->name('event.stop');

	Route::get('/get-events', 'LiveEventController@getEvents')->name('event.youtube-list');
	Route::get('/get-more-events/{page_token}', 'LiveEventController@getMoreEvents')->name('event.more-youtube-list');
	Route::get('/get-event-by-id/{event_id}', 'LiveEventController@getEventById')->name('event.db-single');
	Route::get('/watch-live/{event_id}', 'LiveEventController@live')->name('event.live');
	Route::get('/get-live-id/{user_id}', 'LiveEventController@liveId');



	//favourite routes
	Route::get('/get-all-favourite/', 'FavouriteController@getAllFavourite')->name('favourite.index');
	Route::get('/add-favourite/{followed_id}', 'FavouriteController@addToFavourite')->name('favourite.store');
	Route::get('/remove-favourite/{followed_id}', 'FavouriteController@removeFavourite')->name('favourite.destroy');

	//account ettings routes
	Route::get('change-password', 'UserController@changePass')->name('password.form');
	Route::post('change-password', 'UserController@updatePass')->name('password.update');


});

/*`````````````		 only artists usable routes 		``````````````````````	*/

Route::group(['middleware' => ['auth:web', 'artist']], function () {
	Route::group(['prefix' => 'artist'], function () {
		//page routes for artists
		Route::get('home', 'PageController@artistHome')->name('artist.home');
		Route::get('collections', 'PageController@artistCollection')->name('artist.collections');
	});
});

/*`````````````		 only fans usable routes 		``````````````````````	*/

Route::group(['middleware' => ['auth:web', 'fan']], function () {
	Route::group(['prefix' => 'fan'], function () {
		//page routes for fans
		Route::get('home', 'PageController@fanHome')->name('fan.home');
		Route::get('collections', 'PageController@fanCollection')->name('fan.collections');


	});


	/* 
	*** paypal payment gateway routes 
	*/
	Route::group(['prefix' => 'payments'], function () {
		Route::get('/','PaypalController@index');
		//Route::get('/show/{payment_id}','PaypalController@show');
		Route::get('with-credit-card','PaypalController@paywithCreditCard')->name('payment.creditcard');
		Route::post('with-paypal','PaypalController@paywithPaypal')->name('payment.paypal');

		Route::get('success', 'PaypalController@store');//check if payment is success or fail i have only used pyment id to verify but sometimes the payment may fail and redirect in success.
		Route::get('fails', function () {
		    return 'sorry something went wrong.please go back and try again later if the problem remains';
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