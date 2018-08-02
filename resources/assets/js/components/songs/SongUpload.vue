<template>
    <div class="songupload">
        <!-- upload song link -->
        <div class="form-group">

            <input ref="audioinput" type="file" @change="getFileInfo">

            <input ref="multiaudioinput" type="file" multiple @change="getPlaylistInfo">

        </div>
        <li>
        <a class="btn btn-md btn-default" id="custom-file-upload" data-toggle="modal" data-target="#exampleModal">
            upload song
        </a>

    </li>
        <!-- end of upload song link -->

        <!-- Modal for song information before upload -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">upload song</h5>

                        <button class="btn btn-default btn-md" @click="browseMultiSong">upload mutiple songs at once in a playlist</button>

                         <button class="btn btn-default btn-md" @click="browseSong">upload single song</button>

                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close" @click="resetForm">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <progres v-if="uploadPercentage > 0" :value="uploadPercentage"></progres>
                        <form ref="uploadform" @mousedown="clearError($event)">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <div id="image_previews" v-if="singleup || multiup">
                                            <button type="button" @click="browseImage" class="btn btn-md btn-default">Choose image:</button>
                                                <img ref='image' class="" v-bind:src="song.imgpreview" width="200px" height="200px" >
                                                <input class="form-control-file" ref="imageinput" type="file" name="feature_image" @change="showImage($event)">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div :class="['form-group',errors.filename ? 'has-error' : '']" v-if="singleup">
                                                <label for="title">Song Title:</label>
                                                <input type="text" name="filename" v-model="song.filename" class="form-control" required maxlength="255">
                                                <div v-if="errors.filename">
                                                    <span class="help text-danger" v-text="errors.filename[0]"></span>
                                                </div>
                                            </div>

                                            <div :class="['form-group',errors.name ? 'has-error' : '']" v-if="multiup">
                                                <label for="title">Playlist Title:</label>
                                                <input type="text" name="name" v-model="playlist.name" class="form-control" required maxlength="255">
                                                <div v-if="errors.name">
                                                    <span class="help text-danger"  v-text="errors.name[0]"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <p v-if="singleup" :class="[uploadLimit ? 'text-danger': 'text-success','small']" for="size">Size (in MB): {{ song.filesize }}</p><!-- change text color for upload max limit -->
                                                <div v-if="uploadLimit && !errors.filesize">
                                                    <span class="help text-danger">The filesize may not be greater than 20.(in MB)</span>
                                                </div>
                                                <div v-if="singleup && errors.file">
                                                    <span class="help text-danger" v-text="errors.file[0]"></span>
                                                </div>
                                                <p v-if="multiup" class="text-success small" for="size">Total Size (in MB): {{ playlist.filesize }}</p>
                                                <div v-if=" multiup && errors">
                                                    <div v-for="e in errors">
                                                        <span class="help text-danger" v-text="e.toString()"></span></br>
                                                    </div>
                                                </div>

                                            </div>

                                            <div :class="{'form-group':true,'has-error' : errors.tags}" v-if="singleup" name="tags" >
                                                <label for="genre"> Genre (tag atleast one) </label>
                                                <v-select multiple v-model="song.tags" :options="tags"></v-select><!-- name is inside vueselect component  -->
                                                <div v-if="errors.tags">
                                                    <span class="help text-danger"  v-text="errors.tags[0]"></span>
                                                </div>
                                            </div>

                                            <div :class="{'form-group':true, 'has-error' : errors.upload_type}" v-if="singleup">
                                                <label for="upload_type">Song Upload Type</label>
                                                <select name="upload_type" v-model="song.upload_type" class="form-control">
                                                    <option value="public">public( free )</option>
                                                    <option value="private" v-if="isArtist">private( for sale )</option>
                                                </select>
                                                <div v-if="errors.upload_type">
                                                    <span class="help text-danger"  v-text="errors.upload_type[0]"></span>
                                                </div>
                                            </div>

                                            <div class="form-group" v-if="multiup">
                                               <div class="checkbox">
                                                  <label><input type="checkbox" v-model="playlist.private">private</label>
                                                </div>                                        
                                            </div>

                                            <div :class="{'form-group':true, 'has-error' : errors.amount}" v-if="private">
                                                <label for="upload_type">Song price</label>
                                                <input type="text" name="amount" v-model="song.amount" class="form-control" required maxlength="255">
                                                <div v-if="private && errors.amount">
                                                    <span class="help text-danger" v-text="errors.amount[0]"></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div :class="{'form-group':true, 'has-error' : errors.description}"v-if="singleup">
                                <label for="message-text" class="col-form-label">Description:</label>
                                <textarea class="form-control" id="message-text" v-model="song.description"></textarea>
                                <div v-if="errors.description">
                                    <span class="help text-danger"  v-text="errors.description[0]"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="resetForm" data-dismiss="modal">Cancel</button>

                        <button v-if="singleup" type="button" class="btn btn-primary" @click="uploadSong">upload</button>

                         <button v-else="!singleup" type="button" class="btn btn-primary" @click="uploadPlaylist">upload</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of modal for song info -->
    </div>
</template>

<script>

import vSelect from 'vue-select';
import Progres from './../progress-bars/Progres.vue';

        
export default {

    props: ['tags', 'isArtist'],

    mounted() {
        console.log('song upload Component mounted.');
       // console.log(this.tags);
    },

    components: {vSelect, Progres},

    computed: {
        private() {
            if(this.song.upload_type == 'private') {
                return true;
            }
            return false;
        }
    },

    methods: {
        getFileInfo(event) {
            console.log('file selected');
            this.song.file = event.target.files[0];
            this.song.filename = this.song.file.name;
            this.song.filesize = this.song.file.size/1000000;
            if(this.song.filesize > 20) {
                this.uploadLimit = true;
            }
            //$("#exampleModal").modal('show');         
        },

        getPlaylistInfo(event) {
            
            let files = event.target.files;
            for(let i=0;i < files.length;i++) {
                this.playlist.file.push(files[i]);
                this.playlist.filesize = this.playlist.filesize + files[i].size/1000000;
            }
        },

        browseSong() {
            this.multiup=false;
            this.singleup = true;
            this.resetForm();
            this.$refs.audioinput.click();
        },

        browseMultiSong() {
            this.singleup = false;
            this.multiup=true;
            this.resetForm();
            this.$refs.multiaudioinput.click();
        },

        browseImage() {
            this.$refs.imageinput.click();
        },

        showImage(event) {
            this.song.img = event.target.files[0];
            this.song.imgpreview = URL.createObjectURL(event.target.files[0]);
        },

        resetForm(event) {
            //console.log('trigger');
            this.song.file = '';
            this.song.filename = '';
            this.song.filesize = '';
            this.song.img = '';
            this.song.imgpreview = '';
            this.song.description = '';
            this.uploadLimit = false;
            this.song.upload_type = '';
            this.song.amount = '';
            this.song.tags = '';
            this.errors = {};
            this.uploadPercentage = 0;

            this.playlist.file = [];
            this.playlist.name = '';
            this.playlist.filesize = 0;
            this.playlist.private = '';
        },

        clearError(event) {
            if(event.target.type == 'search') {
                Vue.delete(this.errors,'tags');
            }
            let name = event.target.name;
            //delete this.errors[name]; event the errors is object can use as array to select the property
            Vue.delete(this.errors, name)
        },

        uploadSong() {
            var self = this;
            let formData = new FormData();
            formData.append('file', this.song.file);
            formData.append('filename', this.song.filename);
            formData.append('filesize', this.song.filesize);
            formData.append('img', this.song.img);
            formData.append('description', this.song.description);
            formData.append('upload_type', this.song.upload_type);
            if(this.private) {
                formData.append('amount', this.song.amount);
            }
            if(this.song.tags.length > 0 ){
                formData.append('tags', JSON.stringify(this.song.tags));//sometimes the complex nested objects needs stringify to pass
            }
            //try json encode in song without using formdata.append prevviously used this.song instead self.song so maybe
            axios.post('/artist/songs',formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: function( progressEvent ) {
                    self.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                }
            }).then(function (response) {
                //show progress bar
                //add other fields of song like permission to listen for free or paid content..
                self.resetForm();
                self.$refs.closemodal.click();
                toastr.success(response.data.message);
                
            }).catch(function (error) {
                console.log(error);
                self.errors = error.response.data;
            });
        },

        uploadPlaylist() {//php/apache/php.ini postmaxsize and upload max size 300mb set
            var self = this;
            let formData = new FormData();
            for(let i=0; i < this.playlist.file.length;i++) {
                formData.append('file[]', this.playlist.file[i]);
            }
            //console.log('ps' + this.playlist.filesize);
            formData.append('name', this.playlist.name);
            formData.append('filesize', this.playlist.filesize);
            formData.append('private', this.playlist.private);
            formData.append('img', this.playlist.img);

            axios.post('/playlist/multi',formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: function( progressEvent ) {
                    self.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                }
            }).then(function (response) { //here used self necause callback function does not have lexiacl sccope
                console.log(response.data);
                self.resetForm();
                self.$refs.closemodal.click();
            }).catch(function (error) {
                console.log(error);
                self.errors = error.response.data;
            });
        }
    },

    data() {
        return {
            song: {
                file: '' ,
                filename: '',
                filesize: '',
                img: '',
                imgpreview: '',
                upload_type:'',
                description:'',
                amount:'',
                tags:''
            },

            playlist: {
                file: [],
                name: 'Your Playlist',
                filesize: 0,
                img: '',
                imgpreview: '',
                private:'',
            },

            uploadLimit: false,
            uploadPercentage: 0,

            errors:{},

            singleup:'',
            multiup:''
        }
    }

}

</script>

<style scoped>

input[type="file"] {
    display: none;
}

#image_previews {
    border-radius: 5px;background-color: whitesmoke; width: 200px; height: 200px;
}

.btn{
    border-radius: 0px;
}

</style>

