<template>
	<div class="panel panel-default">

		<div class="panel-heading"> Free Songs </div>
		<div class="form-group">
			<input type="text" v-model="search" class="form-control" placeholder="search songs..">
		</div>

		<div class="panel-body">
			<div class="row">
				<div class="col-md-8">
					<div v-if="songExists" v-for="song in filteredList" class="panel panel-default">
						<div class="panel-heading">
							<img :src="song.user.avatar" width="40px" height="40px">
							{{ song.user.name }}
							<div class="pull-right" >
								<add-playlist :song_id="song.id" :user_id="user_id" :id="id"></add-playlist>
								<manage-song :song="song" :tags="tags" :modalid="id + 'public'">{{ id++ }}</manage-song>
							</div>
						</div>

						<div class="panel-body" @mouseenter="addSongPlayedTime($event, song)">
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
								<i class="fa fa-play" style="font-size: 12px;">{{ song.played_time }}</i>
								{{ song.created_at }}
							</span>
							<like :songs="songs" :id="song.id"></like>
							<share :songs="songs" :id="song.id"></share>

						</div>
						<comment :song="song"></comment>

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
	</div>
</div>
</template>

<script>
	import Aplayer from 'vue-aplayer';
	import Like from './Like.vue';
	import ManageSong from './ManageSong';
	import Comment from './Comment.vue';
	import Share from './Share.vue';


	export default {
//change name to userpubliciview 
	props: ['user_id', 'tags'],

	components: { Aplayer,Like,ManageSong,Comment,Share },

	beforeMount() {
	        //this.getAllSongs();
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
                    });
                }
            });
        },

        search() {

        	this.filteredList = this.songs.filter( (song) => {
        		return song.title.split(" ").join("").toLowerCase().includes(this.search.split(" ").join("").toLowerCase())
        	})
        }
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
	                this.filteredList = this.songs;
	            }

	        }).catch(error => {
	        	console.log(error);
	        });
    	},

        addSongPlayedTime(event, song) {// to send the songid to click event above in clicked watcher so send songplayed incerement request
        	if(this.clicked == song.id) {

        	} else {
            //this.played = false;
            this.clicked = song.id;
        	}
    	}
	},

	data() {
		return {
			songs:[{

				title: '',
				src: '',
				song_description: '',
				image: ''
			}],

			clicked: '',
			played: '',
			filteredList:{},
			search:'',
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