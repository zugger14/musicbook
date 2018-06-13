<template>
    <div>
       <a class="" data-toggle="modal" :data-target="'#PlaylistModal' + id"><span title="add to playlist" class="glyphicon glyphicon-plus"></span></a>
       <!-- Modal for playlist -->
       <div class="modal fade" :id="'PlaylistModal' + id" tabindex="-1" role="dialog" :aria-labelledby="'PlaylistModalLabel' + id" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" :id="'PlaylistModalLabel' + id">Add to Playlist</h5>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="playlistname">Add To New Playlist</label>
                                            <input type="text" v-model="playlist_title" class="form-control" @focus="newplaylist = true">
                                        </div>
                                        <div class="form-group">
                                           <div class="checkbox">
                                              <label><input type="checkbox" v-model="private">private</label>
                                            </div>                                        
                                        </div>
                                    </div>
                                    <div class="col-md-6">OR
                                       <label for="playlist">select your existing playlists:</label>
                                        <div class="form-group">
                                            <select v-model="playlist_id" class="form-control"  @focus="newplaylist = false">
                                                <option selected v-for="(playlist,index) in playlists" :key="playlist.index" :value="playlist.id" >{{ playlist.playlist_title }}</option>

                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button v-if="newplaylist" type="button" class="btn btn-primary" @click="addToNewPlaylist">Save</button>
                        <button v-else="!newplaylist" type="button" class="btn btn-primary" @click="addToPlaylist">Save</button>

                    </div>
                </div>
            </div>
        </div><!-- end of modal -->
    </div>
</template>

<script>
export default {

    props: ['song_id','user_id','id'],

    mounted() {
        console.log('playlist Component mounted.');
        this.getPlaylist();

    },
    watch:{
        playlist_title() {
            console.log(this.playlist_title);
        },

        playlist_id(newval,oldval) { 
            console.log(oldval);
            console.log(newval);
        },
        
        newplaylist() {
            console.log(this.newplaylist);
        }
    },

    methods: {
        addToNewPlaylist() {
            //ad new playlist
            let formData = new FormData();
            formData.append('playlist_title',this.playlist_title);
            formData.append('private',this.private);

            axios.post('/playlist/',formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response =>{
                this.song.playlist_id = response.data;

                //add song to playlist
                axios.post('/playlist/addsong',this.song).then(response =>{
                    console.log(response.data);
                    this.playlist_title = '';
                    this.closemodal();
                    toastr.success('added to your new playlist');
                }).catch(error=>{
                    console.log(error);
                });

                console.log(this.song.playlist_id);
            }).catch(error=>{
                console.log(error);
            });
        },

        getPlaylist() {
            axios.get('/playlist/getplaylist/' + this.user_id).then(response =>{
                if(response != '') {
                    this.playlists = response.data;
                }
            }).catch(error =>{
                console.log(error);
            })
        },

        closemodal() {
            this.$refs.closemodal.click();
        },

        addToPlaylist() {
            this.song.playlist_id = this.playlist_id;
            axios.post('/playlist/addsong',this.song).then(response =>{
                    console.log(response.data);
                    this.playlist_id = '';
                    this.closemodal();
                    toastr.success('added to your playlist');
                }).catch(error=>{
                    console.log(error);
                });   
        }
    },

    data() {
        return {
            playlist_title:'',
            private:'',
            playlist_id: '',
            song:{ playlist_id: '', song_id: this.song_id },
            playlists: '',
            newplaylist:false
        }
    }
}
</script>
