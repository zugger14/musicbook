<template>
    <div>
        <a data-toggle="modal" :data-target="'#EditModal'+ modalid"><span title="edit" class="glyphicon glyphicon-pencil" ></span></a>
        <a class=""><span title="delete" class="glyphicon glyphicon-trash"></span></a>

        <!-- Modal for editing song tracks-->
        <div class="modal fade" :id="'EditModal'+ modalid" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="EditModalLabel">Edit Song</h5>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
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
                                                <img ref='image' class="" v-bind:src="image" width="200px" height="200px" >
                                                <input class="form-control-file" ref="imageinput" type="file" name="feature_image" @change="showImage($event)">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="title">Song Title:</label>
                                                <input type="text" v-model="esong.title" class="form-control" required maxlength="255">
                                            </div>
                                            <div class="form-group">
                                                <label for="upload_type">Song Upload Type</label>
                                                <select name="upload_type" v-model="esong.upload_type" class="form-control">
                                                    <option value="public">public( free )</option>
                                                    <option value="private">private( for sale )</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="message-text" class="col-form-label">Description:</label>
                                                <textarea class="form-control" id="message-text" v-model="esong.song_description"></textarea>
                                            </div>
                                            <div class="form-group" v-if="private">
                                                <label for="upload_type">Song price</label>
                                                <input type="text" v-model="esong.amount" class="form-control" required maxlength="255">

                                            </div>

                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="edit">Save</button>
                    </div>
                </div>
            </div>
        </div><!-- end of modal -->
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('index' + this.index);
        },

        props: ['song','modalid','index'],

        computed: {
            private() {
                if(this.esong.upload_type == 'private') {
                    return true;
                }
                return false;
            }
        },

        methods: {
            edit() {
                let formData = new FormData();
                formData.append('title', this.esong.title);
                formData.append('img', this.esong.img);
                formData.append('description', this.esong.song_description);
                formData.append('upload_type', this.esong.upload_type);
                formData.append('_method', 'PUT')

                axios.post('/artist/songs/' + this.esong.id, formData,{
                    headers: {//no need to put headers here dont know why it works with multipart-fromdata??
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response =>{
                    this.$refs.closemodal.click();
                    toastr.success('successfully edited song.');
                    this.$emit('update', {song:this.esong,index:this.index});
                }).catch(error => {
                    console.log(error);
                });
            },


            delete() {
                // ofr private soongs send request to admin for delete then admin decides when to delete..

                //axios.get('delete')
            },


            browseImage() {
                this.$refs.imageinput.click();
            },

            showImage(event) {
                this.esong.img = event.target.files[0];
                this.image = URL.createObjectURL(event.target.files[0]);
            }

        },

        data() {
                //seperetate data send to databse and just for vuejs part
            return {
                esong:this.song,
                image:this.song.image//image not chnged when using esong as props image only stored..
                                    
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

