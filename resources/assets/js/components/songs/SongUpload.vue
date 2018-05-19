<template>
    <div class="songupload">
        <!-- upload song link -->
        <div class="form-group">
            <input ref="audioinput" type="file" @change="getFileInfo">
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
                                            <div id="image_previews">
                                                <img ref='image' class="" v-bind:src="song.imgpreview" width="200px" height="200px" >
                                                <input class="form-control-file" ref="imageinput" type="file" name="feature_image" @change="showImage($event)">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="title">Song Title:</label>
                                                <input type="text" v-model="song.filename" class="form-control" required maxlength="255">
                                            </div>
                                            <div class="form-group">
                                                <p class="text-success small" for="size">Size (in MB): {{ song.filesize }}</p><!-- change text color for upload max limit -->

                                            </div>
                                            <div class="form-group">
                                                <label for="upload_type">Song Upload Type</label>
                                                <select name="upload_type" v-model="song.upload_type" class="form-control">
                                                    <option value="public">public( free )</option>
                                                    <option value="private">private( for sale )</option>
                                                </select>
                                            </div>
                                            <div class="form-group" v-if="private">
                                                <label for="upload_type">Song price</label>
                                                <input type="text" v-model="song.amount" class="form-control" required maxlength="255">

                                            </div>

                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Description:</label>
                                <textarea class="form-control" id="message-text" v-model="song.description"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="resetForm" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="uploadSong(song)">upload</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of modal for song info -->
    </div>
</template>

<script>
        
export default {

    mounted() {
        console.log('song upload Component mounted.')
    },

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
           // console.log(typeof(this.song.file));
            this.song.filename = this.song.file.name;
            this.song.filesize = this.song.file.size/1000000; 
            /*            console.log(this.song.filename);*/
            //$("#exampleModal").modal('show');         
        },

        browseSong() {
            this.$refs.audioinput.click();
        },

        browseImage() {
            this.$refs.imageinput.click();
        },

        showImage(event) {
            this.song.img = event.target.files[0];//may be i should avoid var and use below inside song.imgelement and song.filelement likewise. use refs in vue js style
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
        },

        uploadSong(song) {
            var self = this;
            let formData = new FormData();
            formData.append('file', this.song.file);
            formData.append('filename', this.song.filename);
            formData.append('filesize', this.song.filesize);
            formData.append('img', this.song.img);
            formData.append('description', this.song.description);
            formData.append('upload_type', this.song.upload_type);

//try json encode in song without using formdata.append prevviously used this.song instead self.song so maybe
            axios.post('/artist/songs',formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function (response) {
                //show progress bar
                //add other fields of song like permission to listen for free or paid content..
                console.log(response);
                self.resetForm();       //this.resetform is not recognized inside here so..
                self.$refs.closemodal.click();
                
            }).catch(function (error) {
                console.log(error);
            });
        }

    },

    data() {
        return {
            song: {
                file: '',
                filename: '',
                filesize: '',
                img: '',
                imgpreview: '',
                upload_type:'',
                description:'',
                amount:''
            }
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

