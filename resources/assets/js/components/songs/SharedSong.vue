<template>
    <div class="songsview">
            <div class="col-md-8">
                <div v-for="song in songs" class="panel panel-default">
                    <div class="panel-heading">
                        <img :src="song.user.avatar" width="40px" height="40px">
                         {{ song.user.name }}{{ song.user.avatar }}
                        
                        <div class="pull-right">
                        </div>
                    </div>

                    <div class="panel-body">
                        <aplayer theme='#FADFA3'
                            :music="{
                            title: song.title,
                            artist: 'Silent Siren',
                            src: song.src,
                            pic: song.image
                            }"
                            :float="true" 
                        />  
                        <div class="panel-body">
                            {{ song.song_description }}
                        </div>
                    </div>

                    <div class="panel-footer">
                        <span class="pull-right">
                            {{ song.created_at }}
                        </span>
                        <like :songs="songs" :id="song.id"></like>
                        <share :songs="songs" :id="song.id"></share>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading"> Most played by users among shared</div>

                    <div class="panel-body" style="height:500px;">
                        songs list                  
                    </div>

                    <div class="panel-footer">

                    </div>
                </div>
            </div>
</div>
</template>

<script>
import Aplayer from 'vue-aplayer';
import Like from './Like.vue';
import Share from './Share.vue';


export default {

    props: ['user_id'],

    components: { Aplayer,Like,Share},
    
    mounted() {
        this.getSharedSongs();
    },

    methods: {
        getSharedSongs() {
            axios.get('/getSharedSongs/' + this.user_id ).then(response => {
                if(response.data !='') { 
                    this.songExists=true;
                    //console.log(response.data);
                    this.songs = response.data;
                    console.log(this.songs);
                }

            }).catch(error => {
                    console.log(error);
            });
        }
    },

    data() {
        return {
            songs:[
                {   
                    user:'',
                    title: '',
                    src: '',
                    song_description: '',
                    image: ''
                }
            ],
        }
    }
}
    
</script>

<style scoped>

</style>