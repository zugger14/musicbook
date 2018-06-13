<template>
    <div class="songview">
        <div class="row">
            <div class="col-md-12">
                <div v-if="songExists" v-for="(song,index) in songs" v-bind:key="song.id" class="panel panel-default">
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
      //  console.log('song views Component mounted.');
    },

    methods: {
        getAllSongs() {//thining of using in computed but no data dependency so better thisway(actually there can be if load more songs option is made then this goes into computed and load more songs will be into method that pushes new song into song object)
            axios.get('/get-purchased-songs/').then(response => {
                if(response.data !='') { 
                    this.songExists = true;
                    this.songs = response.data;
                }

            }).catch(error => {
                    console.log(error);
            });
        },
    },

    data() {
        return {
            songs:{ },
            songExists:false,
            songLocation:'http://localhost:8000/storage/songs/',
        }
    }
}
    
</script>

<style>

</style>