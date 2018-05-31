<template>
    <div class="songview">
        <div class="row">
            <div class="col-md-8">
                <div v-if="songExists" v-for="song in songs" class="panel panel-default">
                    <div class="panel-heading">
                        <img :src="song.user.avatar" width="40px" height="40px">
                         {{ song.user.name }}
                        <div class="pull-right" >
                            <add-playlist :song_id="song.id" :user_id="user_id" :id="id"></add-playlist>
                            <manage-song :song="song" :tags="tags" :modalid="id + 'public'">{{ id++ }}</manage-song>
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
</div>
</template>

<script>
import Aplayer from 'vue-aplayer';
import Like from './Like.vue';
import ManageSong from './ManageSong';

export default {
//change name to userpubliciview 
    props: ['user_id', 'tags'],

    components: { Aplayer,Like,ManageSong },
    
    beforeMount() {
        //this.getAllSongs();
    },
    
    mounted() {
        this.getUserSongs();
        console.log('song views Component mounted.');
    },

    methods: {
        getUserSongs() {
            axios.get('/getusersongs/' + this.user_id ).then(response => {
                if(response.data !='') { 
                    this.songExists=true;
                    this.songs = response.data;
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
                    title: '',
                    src: '',
                    song_description: '',
                    image: ''
                }
            ],

            songExists:false,
            edit:false,
            songLocation:'http://localhost:8000/storage/songs/',
            id:1
        }
    }
}
    
</script>

<style scoped>

</style>