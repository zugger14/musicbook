<template>
    <div class="songview">
        <div class="row">
            <div class="col-md-8">
                <div v-if="songExists" v-for="(song,index) in songs" v-bind:key="song.id" class="panel panel-default">
                    <div class="panel-heading" >
                        <img :src="song.user.avatar" width="40px" height="40px">
                         {{ song.user.name }}
                             <div class="pull-right">
                                <add-playlist :song_id="song.id" :user_id="artist_id" :id="id"></add-playlist>
                                <manage-song v-on:update="update"  :tags="tags" :index="index" :song="song" :modalid="id + 'private'">{{ id++ }}
                                </manage-song>
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

                    <span v-for="tag in song.tags" class="label label-info">{{ tag.name }}</span>
                    <div class="panel-footer">

                        <span class="pull-right">
                            {{ song.created_at }}
                        </span>
                        <like :songs="songs" :id="song.id"></like>
                        <share :songs="songs" :id="song.id"></share>

                        <song-payment v-if="is_artists == false" :song="song"></song-payment>
                    </div>
                    <comment :song="song"></comment>
                </div>
            </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"> Most sold out songs </div>

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
import ManageSong from './ManageSong.vue';
import Comment from './Comment.vue';
import Share from './Share.vue';




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

    props: ['artist_id','is_artist','tags'],

    components: { Aplayer,Like,SongPayment,ManageSong,Comment,Share},
    
    beforeMount() {
        //this.getAllSongs();
    },

    
    mounted() {

        this.getAllSongs();
      //  console.log('song views Component mounted.');
    },

    methods: {
        getAllSongs() {//thining of using in computed but no data dependency so better thisway(actually there can be if load more songs option is made then this goes into computed and load more songs will be into method that pushes new song into song object)
            axios.get('/artist/demos/' + this.artist_id ).then(response => {
              //console.log((response.data))
                if(response.data !='') { 
                    this.songExists=true;
                    this.songs = response.data;
        console.log('demosong' + this.songs.length);
                }

            }).catch(error => {
                    console.log(error);
            });
        },

        update(songdata) {
            console.log(songdata.index);
            let index = songdata.index + 1;
            console.log('newindex' + index);
           // console.log(songdata.song.title);
           // this.songs[songdata.index] = songdata.song;//not reactive due to limit in js caveats

           //the error for not updating in my case seems to be vye player beause only title and image is not changing..

          // this.songs.splice(index, 1, songdata);//in place patch only array item value are updated but dom element is same
           console.log(this.songs[songdata.index].title);
           this.getAllSongs();

        }
    },

    data() {
        return {
            songs:{ },
            songExists:false,
            is_artists: this.is_artist,
            songLocation:'http://localhost:8000/storage/songs/',
            id:1
        }
    }
}
    
</script>

<style>

</style>