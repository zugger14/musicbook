<template>
    <div class="songview">
        <div class="row">
            <div class="col-md-8">
                <div v-if="songExists" v-for="song in songs" class="panel panel-default">
                    <div class="panel-heading">{{ song.title }}</div>

                    <div class="panel-body">
                        <aplayer theme='#FADFA3'
                        :music="{
                            title: song.title,
                            artist: 'Silent Siren',
                            src: song.src,
                            pic: 'storage/images/songcovers/' + song.image
                        }"
                        :float="true" :list="songs" 
                        />  
                        <div class="panel-body">
                            {{ song.description }}
                            descaription about songsss
                        </div>
                    </div>

                    <div class="panel-footer">
                        {{ song.description }}
                        like comments
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
import Aplayer from 'vue-aplayer'

    //let mp3Split = require('mp3-split');

    //let options = {input: 'myaudio.mp3', audios: ['[01:30] My audio']};
    //let split = mp3Split(options);
/*
    split({
        filepath: 'path/to/file.mp4',
        minClipLength: 5,
        maxClipLength: 10
    });
    */
    export default {

        components: { Aplayer },
        beforeMount() {
            this.getAllSongs();
        },
        
        mounted() {
            console.log('Component mounted.');
        },

        methods: {
            getAllSongs() {
                axios.get('/songs').then(response => {
                   /*console.log(JSON.stringify(response.data))*/
                   if(response.data !='') { 
                    this.songExists=true;
                    }
                   console.log(this.songExists);                    
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
                        description: '',
                        image: ''

                    }
                ],
                songExists:false
            }
        }

    }
    </script>
