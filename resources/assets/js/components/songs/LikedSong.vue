<template>
    <div class="songsview">
            <div class="col-md-8">
                <div v-for="song in show_songs" class="panel panel-default">
                    <div class="panel-heading">
                        <img :src="song.user.avatar" width="40px" height="40px">
                         {{ song.user.name }}                        
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
                    <like :songs="songs" :id="song.id"></like>
                    <share :songs="songs" :id="song.id"></share>

                        <span class="pull-right">
                            {{ song.created_at }}
                        </span>
                    </div>
                </div>
                <infinite-loading v-if="songExists" @infinite="infiniteHandler"></infinite-loading>                 
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading"> Most played by users </div>

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

    components: { Aplayer,Like,Share },
    
    mounted() {
        this.getLikedSongs();
    },

    watch: {
        count() {   //for limiting songs to load at beginging
            this.show_songs = this.songs.slice(0,this.count);
            if(this.songs.length < this.count) {
                this.no_data = true;
            }
        }
    },

    methods: {
        getLikedSongs() {
            axios.get('/getLikedSongs/' + this.user_id ).then(response => {
                if(response.data !='') { 
                    this.songExists=true;
                    //console.log(response.data);
                    this.songs = response.data;
                    this.show_songs = this.songs.slice(0,5);
                    console.log(this.songs);
                }

            }).catch(error => {
                    console.log(error);
            });
        },

        infiniteHandler($state) {
            setTimeout(() => {
                this.moreFeeds();
                if(this.no_data == true) {
                    $state.complete();
                } else {
                    $state.loaded();
                }

            }, 500);
        },

        moreFeeds() {
            this.count = this.count + 5 ;
                
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
            show_songs:[],
            no_data:false,
            count:5,
            songExists:false,
        }
    }
}
    
</script>

<style scoped>

</style>