<template>
    <div class="row">
    <div class="container-fluid">
        <div class="col-md-6">
            <div class="panel panel-default most">
                <div class="panel-heading"> Most Played Songs </div>
                <div v-for="(song,index) in top_songs" :key="index" class="row">
                <hr style="width:100%;">                    
                    <div class="panel-body" id="browse">
                        <div class="col-md-9">
                            <aplayer theme='#000005'
                            :music="{
                            title: song.title,
                            artist: ' ',
                            src: song.src,
                            pic: song.image
                            }"
                            :float="true" 
                            /> 
                        </div>
                        <div class="col-md-3">
                            {{ song.title }} by {{ song.user.name }} <!-- slice title in computed -->
                        </div>
                    </div>
                </div>

                <div class="panel-footer"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default recent">
                <div class="panel-heading"> Recently Added Songs </div>
                    <div v-for="(song,index) in recent_songs" :key="index"  class="row">
                    <hr style="width:100%;">                    
                        <div class="panel-body">
                            <div class="col-md-9">
                                <aplayer theme='#000005'
                                :music="{
                                title: song.title,
                                artist: ' ',
                                src: song.src,
                                pic: song.image
                                }"
                                :float="true" 
                                /> 
                            </div>
                            <div class="col-md-3">
                                {{ song.title }} by {{ song.user.name }} <!-- slice title in computed -->
                            </div>
                        </div>                 
                    </div>
                    <div class="panel-footer"></div>     
            </div>
        </div>
    </div>
</div>
</template>

<script>
import Aplayer from 'vue-aplayer';

export default {
    components: { Aplayer},
    
    beforeMount() {
       this.getMostPlayedSongs();
       this.getRecentSongs();

    },

    methods: {

        getMostPlayedSongs() {
            axios.get('/topsongs').then(response => {
                console.log((response.data))
                this.top_songs = response.data;
            }).catch(error => {
                console.log(error);
            });

        },

        getRecentSongs() {
            axios.get('/recentsongs').then(response => {
                console.log((response.data))
                this.recent_songs = response.data;
            }).catch(error => {
                console.log(error);
            });

        },
    },

    data() {
        return {
            songs: [ 
            {
                title: '',
                src: '',
                image: '',

            }
            ],

            top_songs: [],
            recent_songs: [],

            songExists: false,


            songLocation: 'http://localhost:8000/storage/songs/'
        }
    }
}

</script>

<style scoped>
    
    





</style>