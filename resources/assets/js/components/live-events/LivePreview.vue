<template>
        <div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><label class="label label-danger">Currently Streaming Live Event</label></div>
                    <div class="panel-body">
                    <iframe class="pull-left" width="800" height="400" :src="'https://www.youtube.com/embed/'+event_id" frameborder="0" allowfullscreen></iframe>
                    <iframe class="pull-right" allowfullscreen="" frameborder="0" height="400" :src="'https://www.youtube.com/live_chat?v='+event_id+'&embed_domain=localhost'" width="450"></iframe>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {

        props:['event_id'],

        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
