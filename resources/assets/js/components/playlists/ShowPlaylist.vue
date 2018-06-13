<template>
    <div>
        <!-- delte confirm modal -->
        <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="DeleteModalLabel">Delete PLaylist</h5>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                      are you sure to delte this playlist
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" @click="deletePlaylist" >Confirm delete</button>
                    </div>
                </div>
            </div>
        </div><!-- end of modal -->

        <!-- sfilter bar for playlist -->
        <div class="col-md-8">
            <label for="playlist">playlists:</label>
            <div class="form-group">
                <input type="text" v-model="search" class="form-control">
            </div>
        </div>
        <div class="col-md-8" v-for="playlist in filteredList">
            <div class="panel panel-default">
                <div class="panel-heading">{{ playlist.playlist_title }}</label>
                    <label class="text-center" v-if="playlist.playlist_type == 'album' && playlist.album !=null">{{ playlist.album.release_date }}</label>
                    <edit-playlist :playlist="playlist"></edit-playlist>
                    <div class="pull-right">
                        <span title="delete" class="glyphicon glyphicon-trash" @click="storePlaylistId(playlist)" data-target="#DeleteModal" data-toggle="modal" ></span>
                    </div>
                </div>
                <div class="panel-body" v-for="song in playlist.songs">
                    {{ song.title }}
                    {{ song.song_filename }}
                    <publicsong-view :song_id="song.id" :playlist_id="playlist.id" :lists="playlist.songs"></publicsong-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import EditPlaylist  from './EditPlaylist.vue';

export default {

    props: ['user_id'],

    components: {EditPlaylist},
    
    mounted() {
        console.log('playlist Component mounted.');

        this.getPlaylistSongs();
    },

    watch: {
        search() {
            //console.log(this.playlists);
           this.filteredList = this.playlists.filter( (playlist) => {
               return playlist.playlist_title.split(" ").join("").toLowerCase().includes(this.search.split(" ").join("").toLowerCase())
            })
        }
    },

    methods: {
        //get all user playlists and songs inside them as well
        getPlaylistSongs() {
           axios.get('/playlist/songs/' + this.user_id).then(response =>{
                this.playlists = response.data;
                this.filteredList = this.playlists;
        console.log(JSON.stringify(this.playlists));
            }).catch(error =>{
                console.log(error);
            })
        },

        closemodal() {
            this.$refs.closemodal.click();
        },

        storePlaylistId(p) {
            this.playlist_id = p.id;//change as below contains id also and change in  delteplaylist method alos
            this.removedPlaylist = p;
        },

        deletePlaylist() {

            axios.delete('/playlist/' + this.playlist_id).then(response =>{
                console.log(response);
                let index = this.playlists.indexOf(this.removedPlaylist);
                this.closemodal();
                this.playlists.splice(index, 1);

            }).catch(error =>{
                console.log(error);
            });

        }

    },

    data() {
        return {
            playlists: { songs: ''},
            filteredList:{},
            playlist_id:'',
            delete_confirmed: false,
            removedPlaylist:'',
            newtitle:'',
            search:''
        }
    }
}
</script>
