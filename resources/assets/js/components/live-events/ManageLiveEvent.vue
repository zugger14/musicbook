<template>
    <div>
    	<a href="" class="btn btn-md btn-default" data-toggle="modal" :data-target="'#LiveModal' + event_id">Manage live settings</a>

        <div class="modal fade" :id="'LiveModal' + event_id" tabindex="-1" role="dialog" aria-labelledby="LiveModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="LiveModalLabel">{{ event.title }} [{{ event.created_at }}]</h4>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
	                        <div class="col-md-4">
	                        	<img :src="event.image" width="100px" height="100px">
	                        </div>
	                        <div class="col-md-6">
								<div class="form-group">
		                            Stream Url: <input ref="url" disabled class="from-control" type="text" :value="event.stream_url"><span class="fa fa-pencil" @click="copy"></span><br>
		                            Stream Name/Key: <input class="from-control" type="text" :value="event.stream_key"><br>
								</div>
	                        </div>
	                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  data-dismiss="modal">close</button>
                        
                        <button class="btn btn-primary" v-if="event.status=='upcoming' && test_pass==false" @click.prevent="testEvent(event.id)">start event</button>

	                    <button class="btn btn-success" v-if="event.status=='upcoming' && test_pass==true" @click.prevent="startEvent(event.id)">Go live</button>
	                    <button class="btn btn-danger" v-if="event.status=='active'" @click.prevent="stopEvent(event.id)">stop event</button>
                    </div>
                </div>
            </div>
        </div><!-- end of modal -->
    </div>
</template>

<script>
    export default {

    	props:['event_id'],

        mounted() {
            //console.log('Component mounted event.' + this.event_id);
            this.getEvent();
        },

        data() {
        	return {
        		event:{},
        		test_pass: false

        	}
        },

        methods: {
        	getEvent() {
	            axios.get('/get-event-by-id/' + this.event_id).then(response => {
	                this.event = response.data;

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

	        editEvent() {

	        },

	        deleteEvent() {

	        },

	        closeModal() {
	        	this.$refs.closemodal.click();
	        },

	        testEvent(event_id) {
	            axios.get('/test-event/' + event_id).then(response => {
	                if(response.data != '' ) {
	                    console.log(response.data);
	                    this.test_pass = true;
	                }

	            }).catch(error => {
	                console.log(error);
	            })
	        },

	        startEvent(event_id) {
	            axios.get('/start-event/' + event_id).then(response => {
	                console.log(response.data);
	                this.closemodal;

	            }).catch(error => {
	                console.log(error);
	            })
	        },

	        stopEvent(event_id) {
	            axios.get('/stop-event/ ' + event_id).then(response => {
	                console.log(response.data);
	                this.closeModal();

	            }).catch(error => {
	                console.log(error);
	            })
	        }

        }
    }
</script>

