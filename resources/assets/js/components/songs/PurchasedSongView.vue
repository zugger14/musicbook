<template>
    <div class="songview">
        <div class="row">
            <div class="col-md-12">
                <div v-if="songExists" v-for="(song,index) in show_songs" v-bind:key="song.id" class="panel panel-default">
                    <div class="panel-heading" >
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
                        :float="true" :list="songs" 
                        />  
                        <div class="panel-body">
                            {{ song.song_description }}
                        </div>
                    </div>

                    <span v-for="tag in song.tags" class="label label-info">{{ tag.name }}</span>
                    <div class="panel-footer">

                        <span class="pull-right">
                            {{ song.created_at }}
                        </span>

                    </div>
                </div>
                <infinite-loading v-if="songExists" @infinite="infiniteHandler"></infinite-loading>                 
            </div>
    </div>
</div>
</template>

<script>
import Aplayer from 'vue-aplayer';
import ManageSong from './ManageSong.vue';


export default {

    props: ['tags'],

    components: { Aplayer},
    
    beforeMount() {
        //this.getAllSongs();
    },

    mounted() {
        this.getAllSongs();
        //console.log('song views Component mounted.');
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
        getAllSongs() {//thining of using in computed but no data dependency so better thisway(actually there can be if load more songs option is made then this goes into computed and load more songs will be into method that pushes new song into song object)
            axios.get('/get-purchased-songs/').then(response => {
                if(response.data !='') { 
                    this.songExists = true;
                    this.songs = response.data;
                    this.show_songs = this.songs.slice(0,5);
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
            songs:{ },
            show_songs:[],
            no_data:false,
            count:5,
            songExists:false,
            songLocation:'http://localhost:8000/storage/songs/',
        }
    }
}
    
</script>

<style>

</style>