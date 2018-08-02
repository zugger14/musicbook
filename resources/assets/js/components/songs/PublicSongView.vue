<template>
    <div class="songview">
        <div class="row">
            <div class="col-md-12">
                <div v-if="songExists" v-for="song in songs" :key="song.id" class="panel panel-default">
                    <div class="panel-heading">
                        <img :src="song.user.avatar" width="40px" height="40px">
                         {{ song.user.name }}
                        
                        <div class="pull-right">
                            <span class="glyphicon glyphicon-trash" @click="removeSong(song.id, playlist_id)">remove from playlist</span>
                        </div>
                    </div>

                    <div class="panel-body">
                        <aplayer theme='#FADFA3'
                            :music="{
                            title: song.title,
                            artist: ' ',
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
                    <div class="tags">Tagged in:
                            <span v-for="tag in song.tags" class="label label-info">{{ tag.name }}</span>
                    </div>
                        <span class="pull-right">
                            {{ song.created_at }}
                        </span>
                        <like :songs="songs" :id="song.id"></like>
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

    //change name to userpubliciview 
    props: ['song_id', 'playlist_id'],

    components: { Aplayer,Like},
    
    beforeMount() {
        //this.getAllSongs();
    },
    
    mounted() {
        this.getPublicSong();
        console.log('song views Component mounted.');
    },

    methods: {
        getPublicSong() {
            axios.get('/getpublicsong/' + this.song_id ).then(response => {
                if(response.data !='') { 
                    this.songExists=true;
                    this.songs = response.data;
                }

            }).catch(error => {
                    console.log(error);
            });
        },

        removeSong(s_id,p_id) {
            axios.get('/playlist/removesong/' + s_id + '/' + p_id).then(response => {
                if(response.data !='') { 
                    console.log(response);
                }

            }).catch(error => {
                    console.log(error);
            });

        }
    },

    data() {
        return {
            songs:{ src:'' },
            songExists:false,
            songLocation:'http://localhost:8000/storage/songs/',
            id:1
        }
    }
}
    
</script>

<style scoped>

</style>