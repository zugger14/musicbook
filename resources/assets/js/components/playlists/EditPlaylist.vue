<template>
    <div >
        <span title="edit" class="glyphicon glyphicon-pencil" data-toggle="modal" :data-target="'#PlaylistModal' + playlist.id" @click="editMode = true" ></span>

        <!-- Modal for User Playlist update -->
        <div class="modal fade " :id="'PlaylistModal' + playlist.id" tabindex="-1" role="dialog" aria-labelledby="ProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-profile" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ProfileModalLabel">Edit PLaylist</h5>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">

                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label> Playlist Title</label>
                                            <input type="text" class="form-control" v-model="playlis.playlist_title">
                                        </div>
                                        <div class="form-group">
                                            <label> playlist Type</label>
                                            <select v-model="playlis.playlist_type">
                                                <option value="collection">collection</option>
                                                <option value="album">album</option>
                                            </select>
                                        </div>
                                        <div class="form-group" v-if="album">
                                            <label> Album Release Date </label>
                                            <input type="date" class="form-control" v-if="playlis.album == null" v-model="release_date">
                                            <input type="date" class="form-control" v-else v-model="playlis.album.release_date">
                                        
                                        </div>

                                        <div class="form-group">
                                            <label> Private </label>
                                            <input type="checkbox" v-model="playlis.private">
                                        </div>
                                    </div> 
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="editPlaylist">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of modal for profile update -->
    </div>
</template>

<script>
    export default {

        props: ['playlist'],

        mounted() {
            console.log('Component mounted.')
        },

        computed: {
            album() {
                if(this.playlis.playlist_type == 'album') {
                    if (this.playlis.album == null) {
                        this.playlis.album = {};
                    }
                    return true;
                }
                return false;
            }
        },

        methods: {
            editPlaylist() { //add private button also
            if(this.album) {
                if(this.playlis.album == null) 
                    this.playlis.release_date = this.release_date;
                else
                    this.playlis.release_date = this.playlis.album.release_date;

            } 
                axios.put('/playlist/' + this.playlis.id, this.playlis).then(response =>{
                    if(response != '') {
                        this.$refs.closemodal.click();
                        toastr.success('succesfully edited the playlist');
                    }

                }).catch(error =>{
                    console.log(error);
                })
            }
        },

        data() {
            return {
                playlis: this.playlist,
                release_date:''

            }
        }
    }
</script>

