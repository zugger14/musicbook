
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.toastr = require('toastr');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
/*
*/			//Vue Components
/*
*/
Vue.component('song-upload', require('./components/songs/SongUpload.vue'));
Vue.component('demosong-view', require('./components/songs/DemoSongView.vue'));
Vue.component('purchasedsong-view', require('./components/songs/PurchasedSongView.vue'))

Vue.component('song-feeds', require('./components/songs/SongFeeds.vue'))
Vue.component('song-download', require('./components/songs/SongDownload.vue'));
Vue.component('userpublicsong-view', require('./components/songs/UsersPublicSongView.vue'));
Vue.component('publicsong-view', require('./components/songs/PublicSongView.vue'));
Vue.component('liked-song', require('./components/songs/LikedSong.vue'));
Vue.component('shared-song', require('./components/songs/SharedSong.vue'));
Vue.component('song-home', require('./components/songs/SongHome.vue'));


Vue.component('create-live-event', require('./components/live-events/CreateLiveEvent.vue'));
Vue.component('live-events', require('./components/live-events/LiveEvents.vue'));

Vue.component('favourite-view', require('./components/favourites/FavouriteView.vue'));
Vue.component('favourite-add', require('./components/favourites/AddFavourite.vue'));

Vue.component('collections-sidebar', require('./components/collections/CollectionsSideBar.vue'));


Vue.component('pm-sidebar', require('./components/private-messages/PrivateMessageSidebar.vue'));
Vue.component('pm-nav', require('./components/private-messages/PrivateMessageNav.vue'));


Vue.component('friend-button', require('./components/friends/FriendButton.vue'));
Vue.component('friend-requests', require('./components/friends/FriendRequests.vue'));
Vue.component('friends', require('./components/friends/Friends.vue'));

Vue.component('change-profile-pic', require('./components/profile/ChangeProfilePicture.vue'));
Vue.component('edit-profile', require('./components/profile/EditProfile.vue'));

Vue.component('add-playlist', require('./components/playlists/AddPlaylist.vue'));
Vue.component('show-playlist', require('./components/playlists/ShowPlaylist.vue'));

Vue.component('add-note', require('./components/notes/AddNote.vue'));

Vue.component('search-users', require('./components/search/SearchUsers.vue'));

Vue.component('notification', require('./components/notifications/Notification.vue'));

Vue.component('user-login', require('./components/auth/Login.vue'));

Vue.component('InfiniteLoading', require('vue-infinite-loading'));




import VueSocketio from 'vue-socket.io'
import store from './store'
       
Vue.use(VueSocketio, 'http://localhost:8890');

const app = new Vue({
    el: '#app',
    store,
    data: {
        nav: ''
    }
});
/*
	    if(window.location.href.indexOf("collections") > -1) 
        {  
            alert('asd');
            this.nav = 'top';
            console.log('khai' + this.nav);
         } else {
            alert('asdsadasdsaasd');

            this.nav = '';
         }
*/

