<template>
    <div class="songview">
        <div class="row">
            <div class="col-md-8">
                <div v-if="songExists" v-for="song in songs" class="panel panel-default">
                    <div class="panel-heading">
                        <img :src="song.user.avatar" width="40px" height="40px">
                         {{ song.user.name }}
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
                            descaription about songsss
                        </div>
                    </div>

                    <div class="panel-footer">
                        <span class="pull-right">
                            {{ song.created_at }}
                        </span>
                        <like :songs="songs" :id="song.id"></like>
                   
                    </div>
                </div>
            </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"> Most played by users </div>

                <div class="panel-body" style="height:500px;">
                    songs list                  
                </div>

                <div class="panel-footer">
                    like comments
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import Aplayer from 'vue-aplayer';
import Like from './Like.vue';

export default {

    components: { Aplayer,Like },
    
    beforeMount() {
       this.getSongFeeds();
    },
    
    mounted() {
        
        console.log('song feeds Component mounted.');
    },

    methods: {
        getSongFeeds() {
            axios.get('/songfeeds').then(response => {
                //console.log((response.data))
                if(response.data !='') { 
                    this.songExists=true;
                }
                this.songs = response.data;
            }).catch(error => {
                    console.log(error);
            });
        }
    },

    data() {
        return {
            songs:[
                {
                    title: '',
                    src: '',
                    song_description: '',
                    image: ''
                }
            ],

            songExists:false,

            songLocation:'http://localhost:8000/storage/songs/'
        }
    }
}
    
</script>