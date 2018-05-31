<template>
    <div class="songview">
        <div class="row">
            <div class="col-md-8">
                <div v-if="songExists" v-for="(song,index) in songs" :key="index" class="panel panel-default">
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
                        <button @click="hide=!hide">show/hide description</button>
                        <div class="panel-body" v-if="!hide">
                            {{ song.song_description }}
                        </div>
                    </div>

                    <div class="panel-footer">
                        <span class="pull-right">
                            {{ song.created_at }}
                        </span>
                        <div class="row">
                            <div class="col-md-1">
                                <like :songs="songs" :id="song.id"></like><!-- i sent whole songs thats not needed i was imitating the state implemtation so refactor this one -->
                            </div>
                            <div class="col-md-1">
                                <share :songs="songs" :id="song.id"></share>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <comment :song="song"></comment>
                            </div>
                        </div>    
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
import Share from './Share.vue';
import Comment from './Comment.vue';



export default {

    components: { Aplayer,Like,Share,Comment },
    
    beforeMount() {
       this.getSongFeeds();
    },
    
    mounted() {
        
        console.log('song feeds Component mounted.');
    },

    methods: {
        getSongFeeds() {
            axios.get('/songfeeds').then(response => {
                console.log((response.data))
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
                    image: '',
                }
            ],

            songExists:false,
            hide:true,

            songLocation:'http://localhost:8000/storage/songs/'
        }
    }
}
    
</script>