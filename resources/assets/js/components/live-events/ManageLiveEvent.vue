<template>
    <div>
    	<a href="" class="btn btn-md btn-default" data-toggle="modal" :data-target="'#LiveModal' + event_id">live control panel </a>

    	<a href="" class="btn btn-md btn-default" data-toggle="modal" :data-target="'#EditModal' + event_id" v-if="disabled == 'false'"><i class="fa fa-pencil"></i>Edit</a>
<!--         <a href="" class="btn btn-md btn-default" data-toggle="modal" :data-target="'#EditModal' + event_id"><i class="fa fa-pencil"></i>Edit</a>
  not disabled can edit info by putting try catch in binding part in youtube api-->

        <a href="" class="btn btn-md btn-danger"  @click.prevent="deleteEvent(event_id)"><i class="fa fa-trash"></i>delete</a>


    	<!-- live settings modal -->
        <div class="modal fade" :id="'LiveModal' + event_id" tabindex="-1" role="dialog" aria-labelledby="LiveModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="LiveModalLabel">{{ event.title }} [{{ event.created_at }}]</h4>
                        <button type="button" class="close" ref="closelivemodal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
	                        <div class="col-md-4">
	                        	<img :src="img" width="100px" height="100px">
	                        </div>
	                        <div class="col-md-6">
								<div class="form-group">
		                            Stream Url: <input ref="url" disabled class="form-control" type="text" :value="event.stream_url">
		                            Stream Name/Key: <input class="form-control" type="text" :value="event.stream_key">
								</div>
	                        </div>
	                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  data-dismiss="modal">close</button>
                        
                        <button class="btn btn-primary" v-if="event.status=='upcoming' && test_pass==false" @click.prevent="testEvent(event.youtube_event_id)">start event</button>

	                    <button class="btn btn-success" v-if="event.status=='upcoming' && test_pass==true" @click.prevent="startEvent(event.youtube_event_id)">Go live</button>
	                    <button class="btn btn-danger" v-if="event.status=='active'" @click.prevent="stopEvent(event.youtube_event_id)">stop event</button>
                    </div>
                </div>
            </div>
        </div><!-- end of modal -->

        <!-- edit event modal -->
        <div class="modal fade" :id="'EditModal' + event_id" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="EditModalLabel"> Edit Events </h4>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    	<div class="row">
                    	<div class="col-md-4">
	                    	<div class="form-group">
								<div id="image_previews">
		                            <button type="button" @click="browseImage" class="btn btn-md btn-default">Choose image:</button>
		                            <img ref='image' class="" v-bind:src="event.thumbnail_path" width="150px" height="150px" >
		                            <input class="form-control-file" ref="imageinput" type="file" name="feature_image" @change="showImage($event)">
		                        </div>
		                    </div>
                    	</div>
                    	<div class="col-md-8">
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
	                              <label><input type="checkbox" v-model="event.privacy_status">private</label>
	                        </div>
                    	</div>	
                    	</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  data-dismiss="modal">close</button>
                        
                        <button class="btn btn-primary" @click.prevent="editEvent(event.youtube_event_id)">save</button>
                    </div>
                </div>
            </div>
        </div><!-- end of modal -->

    </div>
</template>

<script>
    export default {

    	props:['event_id', 'img', 'disabled'],

        mounted() {
            //console.log('Component mounted event.' + this.event_id);
            this.getEvent();
          //  this.$emit('startlive', 'asd');

        },

        data() {
        	return {
        		event:
        		{
        			title:'',
                    description:'',
                    privacy_status:'',
                    thumbnail_path:'',
                    image:'',
                    date:'',
                    time: ''
        		},

        		test_pass: false

        	}
        },

        methods: {

        	browseImage() {
                this.$refs.imageinput.click();
            },

            showImage(event) {
                this.event.image = event.target.files[0];
                this.event.thumbnail_path = URL.createObjectURL(event.target.files[0]);
            },

        	getEvent() {
	            axios.get('/get-event-by-id/' + this.event_id).then(response => {
	                this.event = response.data;
	                this.event.thumbnail_path = response.data.image; //for thumbnail display of event image
                    if(response.data.private == 1) {//for setting private checkbox as in hte db
                        this.event.privacy_status = true;
                    }
                    let date =  new Date(response.data.schedule_start_datetime);
                    this.event.date = date.toISOString().slice(0,10); 
                    this.event.time = date.toISOString().slice(11,19);

	            }).catch(error => {
	                console.log(error)
	            })
	        },

	        copy() {//bhako chaina
	        	console.log('copy');
               	let el = this.$refs.url;
               	el.select();
				document.execCommand('copy');
	        },

	        editEvent(event_id) {
                //stop edit at the time of live
	        	let formData = new FormData();
                formData.append('title', this.event.title);
                formData.append('description', this.event.description);
                formData.append('thumbnail_path', this.event.thumbnail_path);
                formData.append('privacy_status', this.event.privacy_status);
                formData.append('image', this.event.image);
                formData.append('date', this.event.date);
                formData.append('time', this.event.time);

                axios.post('/edit-event/' + event_id, formData ).then(response => {//when put formdata oesnot work or can spoof using __method=put 
                
                    console.log(response.data);
                    this.event = {};
                    this.closeModal('edit');
                    location.reload();

                }).catch(error => {
                    console.log(error);
                })
	        },

	        deleteEvent(event_id) {
                //console.log(event_id);
                if(confirm('are you sure to delete this event ?')) {  
                    axios.delete('/delete-event/' + event_id).then(response => {
                        if(response.data == 1){
                            //console.log(response.data);
                            location.reload();
                        }

                    }).catch(error => {
                        console.log(error);
                    })
                }

	        },

	        closeModal($select) {
                if($select == 'live') {
    	        	this.$refs.closelivemodal.click();
                }
                this.$refs.closemodal.click();
	        },

	        testEvent(event_id) {
                setTimeout(function() {
                    toastr.info('before starting thi event please make sure you don\'t have other liveevent active');
                },1000);
                axios.get('/test-event/' + event_id).then(response => {
	                if(response.data != '' ) {
	                    console.log(response.data);
                        self = this;
                        setTimeout(function() {
    	                    self.test_pass = true;
                        },5000);

	                }

	            }).catch(error => {
                    console.log(error);
	                let er = error.response.data.message;
                    er = JSON.parse(er);
                    if(er.error.errors[0].reason == "redundantTransition") {
                        self = this;
                        setTimeout(function() {
                            self.test_pass = true;
                        },3000);
                    }
	            })
	        },

	        startEvent(event_id) {
                axios.get('/start-event/' + event_id).then(response => {
                    this.closeModal('live');
                    location.reload();

                }).catch(error => {
                    console.log(error);
                })
	        },

	        stopEvent(event_id) {

                axios.get('/stop-event/' + event_id).then(response => {
                    console.log(response.data);
                    this.closeModal('live');
                    location.reload();

	            }).catch(error => {
	                console.log(error);
	            })
	        }

        }
    }
</script>

