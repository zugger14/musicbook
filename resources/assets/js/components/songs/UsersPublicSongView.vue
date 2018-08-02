<template>
	<div class="panel panel-default">

		<div class="panel-heading"> Free Songs </div>
		<div class="form-group">
			<input type="text" v-model="search" class="form-control" placeholder="search songs..">
		</div>

		<div class="panel-body">
			<div class="row">
				<div class="col-md-9">
					<div v-if="songExists" v-for="(song,index) in filteredList" class="panel panel-default">
						<div class="panel-heading">
							<img :src="song.user.avatar" width="40px" height="40px">
							{{ song.user.name }}
							<div class="pull-right" >
								<add-playlist :song_id="song.id" :user_id="user_id" :id="id"></add-playlist>
								<manage-song v-on:update="update" :index="index" :song="song" :tags="tags" :modalid="index + 'public'"></manage-song>
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
			        <infinite-loading v-if="songExists" @infinite="infiniteHandler"></infinite-loading>                 
				</div>

				<div class="col-md-3" >
					<div class="panel panel-default">
						<div class="panel-heading"> Most played Song </div>
						<div class="panel-body" style="height:500px;">
                        <div class="col-md-12" v-for="song in userSongs">
                        	{{ song.title }}
							<aplayer  :mini=true theme='#FADFA3'
							:music="{
								title: song.title,
								artist: 'Silent Siren',
								src: song.src,
								pic: song.image
							}"
							:float="true" 
							/>
							<hr>   
						</div>      

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
	props: ['user_id', 'tags'],

	components: { Aplayer,Like,ManageSong,Comment,Share },

	beforeMount() {

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
        },

        count() {
            this.filteredList = this.songs.slice(0,this.count);
            if(this.songs.length <= this.count) {
                this.no_data = true;
            }
        }

    },

    mounted() {
    	this.getUserSongs();
    	this.getMostPlayedUserSongs();
    	console.log('song views Component mounted.');
    },

    methods: {
    	getUserSongs() {
    		axios.get('/getusersongs/' + this.user_id ).then(response => {
	            if(response.data !='') { 
	                this.songExists=true;
	                this.songs = response.data;
	                this.filteredList = this.songs.slice(0,5);
	            }
	            console.log(JSON.stringify(response.data));

	        }).catch(error => {
	        	console.log(error);
	        });
    	},

    	getMostPlayedUserSongs() {
    		axios.get('/getmostplayedusersongs/' + this.user_id ).then(response => {
	            if(response.data !='') { 
	                this.songExists=true;
	                this.userSongs = response.data;
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
    	},

    	update(songdata) {
            this.getUserSongs();
         	this.getMostPlayedUserSongs();
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
			songs:[{

				title: '',
				src: '',
				song_description: '',
				image: ''
			}],

			clicked: '',
            no_data:false,
            count:5,
			userSongs:{},
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