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
                        pic: 'storage/images/songcovers/' + song.image
                        }"
                        :float="true" :list="songs" 
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
                        <song-payment :song="song"></song-payment>
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
import SongPayment from './SongPayment.vue';


//import Crunker from 'crunker/src/crunker.js';
/*var FfmpegCommand = require('fluent-ffmpeg');
var command = new FfmpegCommand();
console.log(command);
*/
//console.log(audioconcat);
/*var songs = [
  'beatles.mp3',
  'greenday.mp3',
  'u2.mp3'
];
*/ 
/*var audioconcat = require('audioconcat');
audioconcat(this.songs.src)
.concat(this.songs.src)
  .on('start', function (command) {
    console.log('ffmpeg process started:', command)
})*/
  /*.concat('all.mp3')
  .on('start', function (command) {
    console.log('ffmpeg process started:', command)
  })
  .on('error', function (err, stdout, stderr) {
    console.error('Error:', err)
    console.error('ffmpeg stderr:', stderr)
  })
  .on('end', function (output) {
    console.error('Audio created in:', output)
});*/

/*var audio = require('crunker');
 

*/
                     /*let audios = new Crunker();
                    console.log(audios);
                    */              


/* audio.fetchAudio('a.mp3','as.mp3')
    .then(buffers => audio.mergeAudio(buffers))
    .then(merged => audio.export(merged, 'audio/mp3'))
    .then(output => audio.download(output.blob))


Crunker.notSupported(() => {
    // Handle no browser support
})*/

export default {

    props: ['artist_id'],

    components: { Aplayer,Like,SongPayment },
    
    beforeMount() {
        this.getAllSongs();
    },
    
    mounted() {
        
        console.log('song views Component mounted.');
    },

    methods: {
        getAllSongs() {//thining of using in computed but no data dependency so better thisway(actually there can be if load more songs option is made then this goes into computed and load more songs will be into method that pushes new song into song object)
            axios.get('/artist/demos/' + this.artist_id ).then(response => {
              //console.log((response.data))
                if(response.data !='') { 
                    this.songExists=true;
                    this.songs = response.data;
                }

            }).catch(error => {
                    console.log(error);
            });
        },

        buyAction() {
            axios.post('/artist/songs/buy').then(response => {
                if(response.data !='') {
                    console.log('rseponse data after inserting into order model: ' + response.data);
                } else {
                    console.log(' order insert response is empty..something wrong from return side');
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

            songLocation:'http://localhost:8000/storage/songs/'
        }
    }
}
    
</script>

<style>

</style>