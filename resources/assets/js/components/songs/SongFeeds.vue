<template>
    <div class="songview">
        <div class="row">
            <div class="col-md-8">
                <div v-if="songExists" v-for="(song,index) in show_songs" :key="index" class="panel panel-default">
                    <div class="panel-heading">
                        <img :src="song.user.avatar" width="40px" height="40px">
                         <label v-if="song.shared == true"> <b v-for="share in song.share"> {{ share.user.name }}</b> shared {{ song.user.name }}'s song </label>
                         <label v-else> {{ song.user.name }} <favourite-add :user="song.user"></favourite-add></label>
                    </div>

                    <div class="panel-body" @mouseenter="addSongPlayedTime($event, song)">
                        <aplayer theme='#000005'
                        :music="{
                            title: song.title,
                            artist: 'Silent Siren',
                            src: song.src,
                            pic: song.image
                        }"
                        :float="true" 
                        /> 

                    </div>

                    <div class="panel-footer">
                        <span class="pull-right">
                            <i class="fa fa-play" style="font-size: 12px;">{{ song.played_time }}</i>
                            {{ song.created_at }}
                        </span>
                        <div class="row">
                            <div class="col-md-1">
                                <like :songs="songs" :id="song.id"></like><!-- i sent whole songs thats not needed i was imitating the state implemtation so refactor this one -->
                            </div>
                                <song-payment v-if="song.upload_type == 'private' && is_artist == 0" :song="song"></song-payment>
                            <div class="col-md-1">
                                <share :songs="songs" :id="song.id"></share>
                            </div>
                        </div>
                        <button  @click="toggleDesc(song)">show/hide description</button>
                        <div class="panel-body" v-if="!hide && songdesc == song.id">
                            {{ song.song_description }}
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <comment :song="song"></comment>
                            </div>
                        </div>    
                    </div>   
                </div>
        <infinite-loading v-if="songExists" @infinite="infiniteHandler"></infinite-loading>                 
        </div>  
        <div class="col-md-2">
            <div class="panel panel-default most">
                <div class="panel-heading"> Most Played Songs </div>
                <div v-for="(song,index) in top_songs" :key="index" class="row">
                <hr style="width:100%;">                    
                    <div class="panel-body" @mouseenter="addSongPlayedTime($event, song)">
                        <div class="col-md-6">
                            <aplayer :mini=true theme='#000005'
                            :music="{
                            title: song.title,
                            artist: 'Silent Siren',
                            src: song.src,
                            pic: song.image
                            }"
                            :float="true" 
                            /> 
                        </div>
                        <div class="col-md-6">
                            {{ song.title }} by {{ song.user.name }} <!-- slice title in computed -->
                        </div>
                    </div>
                </div>
                <div class="panel-footer">like comments</div>
            </div>
        </div>

        <div class="col-md-2">
            <div v-for="(song,index) in recent_songs" :key="index" class="panel panel-default recent">
                <div class="panel-heading"> Recently Added Songs </div>
                <div class="panel-body" @mouseenter="addSongPlayedTime($event, song)">
                    <aplayer :mini=true theme='#000005'
                    :music="{
                    title: song.title,
                    artist: 'Silent Siren',
                    src: song.src,
                    pic: song.image
                    }"
                    :float="true" 
                    /> 
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
import SongPayment from './SongPayment.vue';
//import InfiniteLoading from 'vue-infinite-loading';

export default {

    props: ['is_artist'],

    components: { Aplayer,Like,Share,Comment,SongPayment },
    
    beforeMount() {
       this.getSongFeeds();
       this.getMostPlayedSongs();
       this.getRecentSongs();

    },

    watch: {

        clicked() {
            var self = this;
            $(".aplayer-pic").unbind('click');//need to unbind many click events added whenever new mouse is over new panel body or a song panel and adding only one below
            $(".aplayer-pic").on('click', function(event) {
                if(self.played == self.clicked) {
                    //the same song is clicked so maybe paused so no count increase do nothing.
                } else {
                    axios.get('/addSongPlayedTime/' + self.clicked).then(response => {
                        console.log('return' + response.data);
                        self.played = self.clicked;
                       /* self.songs.forEach((song)=> {
                            if(song.id == self.clicked) {
                                //aile yo vue-aplayer bhann el egarda ho esko bhitra ko file chlauana man nai lagena garo hola jasto cha data pass garna 
                                //song.played_time = response.data; //cannot update value after immediate play
                            }
                        });*/
                    });
                }
            });
        },

        count() {   //for limiting songs to load at beginging
            this.show_songs = this.songs.slice(0,this.count);
            if(this.songs.length < this.count) {
                this.no_data = true;
            }
        }
    },

    methods: {

        getSongFeeds() {
            axios.get('/songfeeds').then(response => {
                console.log((response.data))
                if(response.data !='') { 
                    this.songExists=true;
                    this.songs = response.data;
                    this.show_songs = this.songs.slice(0,5);
                } else {
                    this.no_data = true;    
                }
            }).catch(error => {
                console.log(error);
            });
        },

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

        addSongPlayedTime(event, song) {// to send the songid to click event above in clicked watcher so send songplayed incerement request
            if(this.clicked == song.id) {

            } else {
                this.clicked = song.id;
            }
        },

        toggleDesc(song) {
            if(this.songdesc == song.id) {
                if(this.hide == false) {
                    this.hide = true;
                } else {
                    this.hide= false;
                }
            } else {
                this.songdesc  = song.id;
                this.hide = false;
            }
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
            songs: [ 
            {
                title: '',
                src: '',
                song_description: '',
                image: '',
                user:{}
            }
            ],

            show_songs:[],
            no_data:false,
            count:5,

            top_songs: [],
            recent_songs: [],

            clicked: '',
            played: '',
            
            songExists: false,
            hide: true,
            songdesc:'',

            songLocation: 'http://localhost:8000/storage/songs/'
        }
    }
}

</script>

<style scoped>
    
    





</style>