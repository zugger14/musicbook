<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <ul class="nav nav-pills nav-stacked pm-sidebar">
                    <li><a href="#" :class="currentView == 'demosong-view' ? 'active' :''" @click="currentView='demosong-view'">Demo Songs For Sale</a></li>
                    <li><a href="#" :class="currentView == 'userpublicsong-view' ? 'active' : ''" @click="currentView='userpublicsong-view'"> Free Songs</a></li>
                    <li><a href="#" :class="currentView == 'shared-song' ? 'active' : ''" @click="currentView='shared-song'">Sared Songs</a></li>
                    <li><a href="#" :class="currentView == 'liked-song' ? 'active' : ''" @click="currentView='liked-song'">Likded Songs</a></li>
                    <li><a href="#" :class="currentView == 'purchasedsong-view' ? 'active' : ''" @click="currentView='purchasedsong-view'">purchased Songs</a></li>
                    <li><a href="#" :class="currentView == 'show-playlist' ? 'active' : ''" @click="currentView='show-playlist'">playlists</a></li>
                    <li><a href="#" :class="currentView == 'live-events' ? 'active' : ''" @click="currentView='live-events'">live event</a></li>
                    <li><a href="#" :class="currentView == 'add-note' ? 'active' : ''" @click="currentView='add-note'">Notes</a></li>
                    <li><a href="#" :class="currentView == 'favourite-view' ? 'active' : ''" @click="currentView='favourite-view'">favourites</a></li>
                </ul>
            </div>
            <div class="col-sm-10">
                <component :is="currentView"  :user_id="user_id" :tags="tags" :artist_id="user_id" :is_artist="true"></component>
            </div>
        </div> 
    </div>
 </template>

<script>
    
    export default {

        props: ['user_id','tags'],

        mounted() {
            console.log('Component mounted.')
        },

        data() {
            return {
                currentView: 'demosong-view',
            }
        }
    }
</script>

<style>
    .pm-sidebar.nav-pills a.active {

        background-color:#2c8fbb;
        color:#fff;

    }
 
    .pm-sidebar {
        position: fixed;     
    }

</style>
