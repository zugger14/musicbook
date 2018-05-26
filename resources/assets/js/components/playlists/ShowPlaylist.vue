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
                      are you sure to delte thi splyist
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
                <select v-model="playlist_id" class="form-control">
                    <option selected v-for="(playlist,index) in playlists" :key="playlist.index" :value="playlist.id" >{{ playlist.playlist_title }}</option>

                </select>
            </div>
        </div>
        <div class="col-md-8" v-for="playlist in playlists">
            <div class="panel panel-default">
                <div class="panel-heading"><label v-if="!editMode"> {{ playlist.playlist_title }}</label> <input type="text" v-model="playlist.playlist_title" v-if="editMode">
                    <span v-if="!editMode" title="edit" class="glyphicon glyphicon-pencil" @click="editMode = true" ></span>
                    <div v-if="editMode">
                        <button class="btn btn-success btn-sm" @click="editPlaylist(playlist)"> save </button> 
                        <button class="btn btn-success btn-sm" @click="cancelEdit"> cancel </button> 
                    </div>
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
export default {

    props: ['user_id'],

    mounted() {
        console.log('playlist Component mounted.');
        
        this.getPlaylistSongs();

    },

    watch: {
       'playlists.playlist_title': function() { //not woring so i have instead loaded all data once again thorug api call if canceled
            console.log('changing');
       }
    },

    methods: {
        //get all user playlists and songs inside them as well
        getPlaylistSongs() {
          // /  console.log('entering');
           axios.get('/playlist/songs/' + this.user_id).then(response =>{
                //if(response != '') {
                    this.playlists = response.data;
                    console.log(this.playlists);
                    //console.log('okkk');
                //}
            }).catch(error =>{
                console.log(error);
            })
 
        },

        editPlaylist(playlist) { //add private button also 
            axios.put('/playlist/' + playlist.id, playlist).then(response =>{
                if(response != '') {
                    //this.playlists = response.data;
                    this.editMode = false;
                    //let index = this.playlists.indexOf(playlist); no need as v-mmodel automatically updates the values
                   // this.playlists.splice(index, 1, response.data);
                    console.log(response.data);
                }
            }).catch(error =>{
                console.log(error);
            })
        },

        cancelEdit() {
            this.editMode = false;
            this.getPlaylistSongs();
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
            playlist_id:'',
            delete_confirmed: false,
            removedPlaylist:'',
            editMode:false,
            newtitle:''
        }
    }
}
</script>
