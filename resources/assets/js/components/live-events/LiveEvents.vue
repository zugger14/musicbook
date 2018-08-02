<template>
    <div>
        <div v-if="event_id">
            <live-preview :event_id="event_id"></live-preview>   
        </div>   
        <div class="panel panel-default">
            <div class="panel-heading">Upcoming Live Events</div>
            <div class="panel-body"  v-for="(event, index) in events">
                    <div class="row">
                        <div class="col-md-1">
                            <a :href="'/watch-live/' + event.id">
                                <img :src="event.snippet.thumbnails.high.url" width="100px" height="100px">
                            </a>
                        </div>
                        <div class="col-md-1" v-show="event.snippet.actualEndTime==null || event.snippet.actualStartTime==null">
                            <p>
                                <strong>{{ event.snippet.title }} <label v-show="event.snippet.actualEndTime==null && event.snippet.actualStartTime!=null" class="label label-danger">Live Now</label></strong>
                            </p>
                            {{ event.snippet.description }}
                        </div>
                        <div class="col-md-1" v-show="event.snippet.actualEndTime!=null && event.snippet.actualStartTime!=null">
                            <p><strong>{{ event.snippet.title }}</strong></p>
                            <p class="alert alert-success small">Completed</p>
                        </div>
                        <manage-live-event :disabled="(event.snippet.actualEndTime!=null && event.snippet.actualStartTime!=null) ? 'true' : 'false'" :img="event.snippet.thumbnails.high.url" :event_id="event.id" ></manage-live-event>
                    </div>
            </div>
            <button @click="getMoreEvents"> more</button>
            <div class="panel-body" v-show="empty">
                No any live events made..
            </div>
        </div>
    </div>
</template>

<script>

import ManageLiveEvent from './ManageLiveEvent.vue'
import LivePreview from './LivePreview.vue'


export default {


    components:{ ManageLiveEvent, LivePreview},

    props: ['user_id'],

    mounted() {
        console.log('live mounted.')
        console.log(this.user_id);
        this.getEvents();
        self = this;
        setTimeout(function () {
            self.getLiveVideoId();
        },2000);

    },
    
    data() {
        return {
            events: {

            },

            nextPageToken :'',
            event_id:'',
            empty: false
        }
    },

    methods: {

        getLiveVideoId()
        {
            axios.get('/get-live-id/' + this.user_id).then(response => {

                this.event_id = response.data;
                //console.log('asd' + this.event_id);

            }).catch(error => {
                console.log(error)
            })
        },

        getEvents() {
            axios.get('/get-events/').then(response => {
                if(response.data.length < 1) {
                    this.empty = true;
                } else {
                    this.events = response.data[0];
                    console.log(JSON.stringify(this.events));
                    this.nextPageToken = response.data[1];
                    //console.log(response.data[0]);
                }

            }).catch(error => {
                console.log(error)
            })
        },

        getMoreEvents() {
            axios.get('/get-more-events/' + this.nextPageToken).then(response => {
                if(response.data.length < 1) {
                    this.empty = true;
                } else {
                    this.events = this.events.concat(response.data[0]);
                    this.nextPageToken = response.data[1];
                    //console.log(response.data[0]);
                }

            }).catch(error => {
                console.log(error)
            })
        }
    }
}

</script>
