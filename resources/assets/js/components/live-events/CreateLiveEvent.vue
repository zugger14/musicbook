<template>
    <div> 
        <a href="" class="btn btn-md btn-default" data-toggle="modal" data-target="#EventModal">Create Live Event</a>
        <!-- Modal for Creating Live Event before Google Auth Login-->
        <div class="modal fade" id="EventModal" tabindex="-1" role="dialog" aria-labelledby="EventModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="EventModalLabel">Live Event Info</h5>
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
                                            <div id="image_previews">
                                                <button type="button" @click="browseImage" class="btn btn-md btn-default">Choose image:</button>
                                                <img ref='image' class="" v-bind:src="event.thumbnail_path" width="200px" height="200px" >
                                                <input class="form-control-file" ref="imageinput" type="file" name="feature_image" @change="showImage($event)">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="title">Live Event Title:</label>
                                                <input type="text"  v-model="event.title" class="form-control" required maxlength="255">
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Live Event Description:</label>
                                                <input type="text"  v-model="event.description" class="form-control" required maxlength="255">
                                            </div>
                                            <div class="form-group">
                                                <label for="date">Live Event Date </label>
                                                <input type="date" v-model="event.date" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="time">Live Event Time </label>
                                                <input type="time" v-model="event.time" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                               <div class="checkbox">
                                                  <label><input type="checkbox" v-model="event.privacy_status">private</label>
                                                </div>                                        
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="form-group">
                                <label for="info" class="col-form-label">You can further manage your event from youtube directly for more advance options</label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="createEvent">create</button>
                    </div>
                </div>
            </div>
        </div><!-- end of modal -->
    </div>
</template>

<script>
    export default {

        props: ['auth_token'],

        mounted() {
            console.log('Component mounted.')
            console.log(this.auth_token);
        },

        methods: {

            browseImage() {
                this.$refs.imageinput.click();
            },

            showImage(event) {
                this.event.image = event.target.files[0];
                this.event.thumbnail_path = URL.createObjectURL(event.target.files[0]);
            },

            closeModal() {
                this.$refs.closemodal.click();
            },

            createEvent() {

                let formData = new FormData();
                formData.append('title', this.event.title);
                formData.append('description', this.event.description);
                formData.append('thumbnail_path', this.event.thumbnail_path);
                formData.append('privacy_status', this.event.privacy_status);
                formData.append('image', this.event.image);
                formData.append('date', this.event.date);
                formData.append('time', this.event.time);

                formData.append('auth_token', JSON.stringify(this.auth_token));//[object object ] and cannot decode in lravel if not stringified

                axios.post('/create-event', formData ).then(response => {

                    this.event = {};
                    this.closeModal();
                    location.href="http://localhost:8000/live-events";

                }).catch(error => {
                    console.log(error);
                })
            }

        },

        data() {
            return {
                event: { 
                    title:'',
                    description:'',
                    privacy_status:'',
                    thumbnail_path:'',
                    image:'',
                    date:'',
                    time: ''
                }
            }
        }
    }
</script>


<style>
    input[type="file"] {
    display: none;
}

</style>
