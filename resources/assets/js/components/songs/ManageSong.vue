<template>
    <div>
        <a data-toggle="modal" :data-target="'#EditModal'+ modalid" @click="select(song)"><span title="edit" class="glyphicon glyphicon-pencil" ></span></a>
        <a @click="deletes"><span title="delete" class="glyphicon glyphicon-trash"></span></a>

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
                                                <label for="genre"> Genre (tag atleast one) </label>
                                                <v-select :placeholder="'choose tag'" v-model="tagids" label="name" multiple :options="tags"></v-select>
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
import vSelect from 'vue-select'


    export default {

        props: ['song','modalid','index','tags'],

        components: {vSelect},
        
        mounted() {

        },

        computed: {
            private() {
                if(this.esong.upload_type == 'private') {
                    return true;
                }
                return false;
            },
        },

        methods: {
            
            select(song) { //no need for this method idont know why i did this i will remove it later
                console.log(song.title);
                this.getTagIds(this.song);   
            },

            edit() {
                console.log(this.tagids);
                let formData = new FormData();
                formData.append('title', this.esong.title);
                formData.append('img', this.esong.img);
                formData.append('description', this.esong.song_description);
                formData.append('upload_type', this.esong.upload_type);
                formData.append('amount', this.esong.amount);
                if(this.tagids.length > 0 ){
                    formData.append('tags', JSON.stringify(this.tagids));
                } 
                formData.append('_method', 'PUT');

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

            deletes() { //delete name gives error
                // only chnage status of private songs to removed so that fans who have bought can still acces the songs from their collections but no new purchase option will be available for new fans.
               if(confirm('are you sure you want to delete this song? Theres no undoing this.')) {
                    axios.delete('/artist/songs/' + this.song.id).then(response => {
                        console.log(response.data);
                        location.reload();
                    }).catch(error => {
                        console.log(error);
                    });
               }
            },

            getTagIds(song) {

                axios.post('/gettagids', song ).then(response =>{
                  //  console.log(response.data);
                    this.tagids = response.data;

                }).catch(error =>{
                    console.log(error);
                });
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

                esong: this.song,
                tagids: [],
                name:'name',
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



