<template>
    <div class="songupload">
        <!-- upload song link -->
        <div class="form-group">

            <input ref="audioinput" type="file" @change="getFileInfo">

            <input ref="multiaudioinput" type="file" multiple @change="getPlaylistInfo">

        </div>
        <li>
        <a class="btn btn-md btn-default" id="custom-file-upload" data-toggle="modal" data-target="#exampleModal" @click="browseSong">
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

                        <button v-if="singleup" class="btn btn-default btn-md" @click="browseMultiSong">upload mutiple songs at once in a playlist</button>

                         <button v-if="!singleup" class="btn btn-default btn-md" @click="browseSong">upload single song</button>

                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close" @click="resetForm">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form ref="uploadform">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <button type="button" @click="browseImage" class="btn btn-md btn-default">Choose image:</button>
                                            <div id="image_previews" v-if="singleup">
                                                <img ref='image' class="" v-bind:src="song.imgpreview" width="200px" height="200px" >
                                                <input class="form-control-file" ref="imageinput" type="file" name="feature_image" @change="showImage($event)">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group" v-if="singleup">
                                                <label for="title">Song Title:</label>
                                                <input type="text" v-model="song.filename" class="form-control" required maxlength="255">
                                            </div>

                                            <div class="form-group" v-else="!singleup">
                                                <label for="title">Playlist Title:</label>
                                                <input type="text" v-model="playlist.name" class="form-control" required maxlength="255">
                                            </div>
                                            <div class="form-group">
                                                <p v-if="singleup" class="text-success small" for="size">Size (in MB): {{ song.filesize }}</p><!-- change text color for upload max limit -->

                                                <p v-else class="text-success small" for="size">Total Size (in MB): {{ playlist.filesize }}</p>
                                            </div>

                                            <div class="form-group" v-if="singleup">
                                                <label for="genre"> Genre (tag atleast one) </label>
                                                <v-select multiple v-model="song.tags" :options="tags" ></v-select>
                                            </div>

                                            <div class="form-group" v-if="singleup">
                                                <label for="upload_type">Song Upload Type</label>
                                                <select name="upload_type" v-model="song.upload_type" class="form-control">
                                                    <option value="public">public( free )</option>
                                                    <option value="private">private( for sale )</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                               <div class="checkbox">
                                                  <label><input type="checkbox" v-model="playlist.private">private</label>
                                                </div>                                        
                                            </div>

                                            <div class="form-group" v-if="private">
                                                <label for="upload_type">Song price</label>
                                                <input type="text" v-model="song.amount" class="form-control" required maxlength="255">

                                            </div>

                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="form-group" v-if="singleup">
                                <label for="message-text" class="col-form-label">Description:</label>
                                <textarea class="form-control" id="message-text" v-model="song.description"></textarea>
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

import vSelect from 'vue-select'
        
export default {

    props: ['tags'],

    mounted() {
        console.log('song upload Component mounted.');
        console.log(this.tags);
    },

    components: {vSelect},

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
           //console.log(typeof(this.song.file));
            this.song.filename = this.song.file.name;
            this.song.filesize = this.song.file.size/1000000; 
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
            this.singleup = true;
            this.$refs.audioinput.click();
        },

        browseMultiSong() {
            this.singleup = false;
            this.$refs.multiaudioinput.click();
        },


        browseImage() {
            this.$refs.imageinput.click();
        },

        showImage(event) {
            this.song.img = event.target.files[0];
            //console.log(this.song.img);
            this.song.imgpreview = URL.createObjectURL(event.target.files[0]);
        },

        resetForm(event) {
            //console.log('trigger');
            this.song.file = '',
            this.song.filename = '',
            this.song.filesize = '',
            this.song.img = '',
            this.song.imgpreview = '',
            this.song.description = ''

            this.playlist.file = [];
            this.playlist.filesize = 0;
            this.playlist.private = '';


        },

        uploadSong() {
            var self = this;
            let formData = new FormData();
    
            formData.append('file[]', this.song.file[i]);
            formData.append('filename', this.song.filename);
            formData.append('filesize', this.song.filesize);
            formData.append('img', this.song.img);
            formData.append('description', this.song.description);
            formData.append('upload_type', this.song.upload_type);
            formData.append('amount', this.song.amount);
            formData.append('tags', JSON.stringify(this.song.tags));//sometimes the complex nested objects needs stringify to pass
//try json encode in song without using formdata.append prevviously used this.song instead self.song so maybe
            axios.post('/artist/songs',formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function (response) {
                //show progress bar
                //add other fields of song like permission to listen for free or paid content..
                console.log(response.data);
                self.resetForm();       //this.resetform is not recognized inside here so..
                self.$refs.closemodal.click();
                
            }).catch(function (error) {
                console.log(error);
            });
        },

        uploadPlaylist() {//php/apache/php.ini postmaxsize and upload max size 300mb set
            var self = this;
            let formData = new FormData();
            for(let i=0;i < this.playlist.file.length;i++) {
                formData.append('file[]', this.playlist.file[i]);

            }
            //console.log('ps' + this.playlist.filesize);
            formData.append('name', this.playlist.name);
            formData.append('filesize', this.playlist.filesize);
            formData.append('private', this.playlist.private);
            formData.append('img', this.playlist.img);


//try json encode in song without using formdata.append prevviously used this.song instead self.song so maybe
            axios.post('/playlist/multi',formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function (response) {
               console.log(response.data);
                self.resetForm();
                self.$refs.closemodal.click();
                
            }).catch(function (error) {
                console.log(error);
            });

        }

    },

    data() {
        return {
            song: {
                file: [],
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

            singleup:true,
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

