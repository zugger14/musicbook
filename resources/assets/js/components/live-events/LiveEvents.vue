<template>
    <div>      
        <div class="panel panel-default">
            <div class="panel-heading">Upcoming Live Events</div>
            <div class="panel-body"  v-for="(event, index) in events">
                    <div class="row">
                        <div class="col-md-1">
                            <img :src="event.snippet.thumbnails.high.url" width="100px" height="100px">
                        
                        </div>
                        <div class="col-md-4" v-show="event.snippet.actualEndTime==null || event.snippet.actualStartTime==null">
                            <p>
                                <strong>{{ event.snippet.title }} <label v-show="event.snippet.actualEndTime==null && event.snippet.actualStartTime!=null" class="label label-danger"> Live Now </label></strong>
                            </p>
                            {{ event.snippet.description }}
                            <manage-live-event :event_id="event.id" ></manage-live-event>
                        </div>
                        <div class="col-md-4" v-show="event.snippet.actualEndTime!=null && event.snippet.actualStartTime!=null">
                            <p><strong>{{ event.snippet.title }}</strong></p>
                           <p class="alert alert-success"> Event Completed</p>
                        </div>
                    </div>
            </div>
            <div class="panel-body" v-show="empty">
                No any live events have been scheduled yet..
            </div>
        </div>

    </div>
</template>

<script>

import ManageLiveEvent from './ManageLiveEvent.vue'

export default {

    components:{ ManageLiveEvent},

    mounted() {
        console.log('Component mounted.')
        this.getEvents();
    },
    
    data() {
        return {
            events: {

            },

            empty: false
        }
    },

    methods: {
        getEvents() {
            axios.get('/get-events').then(response => {
                if(response.data == 0) {
                    this.empty = true;
                } else {
                    this.events = response.data[0];
                    if(this.events.length < 1) 
                        this.empty = true;
                }

            }).catch(error => {
                console.log(error)
            })
        }
    }
}

</script>
