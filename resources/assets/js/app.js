
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
Vue.component('song-upload', require('./components/songs/SongUpload.vue'));
Vue.component('demosong-view', require('./components/songs/DemoSongView.vue'))
Vue.component('song-feeds', require('./components/songs/SongFeeds.vue'))
Vue.component('song-download', require('./components/songs/SongDownload.vue'));

Vue.component('friend', require('./components/friends/Friend.vue'));
Vue.component('friend-requests', require('./components/friends/FriendRequests.vue'));

Vue.component('notification', require('./components/notifications/Notification.vue'));



Vue.component('user-login', require('./components/auth/Login.vue'));

import {store} from './store'

const app = new Vue({
    el: '#app',
    store,
});
