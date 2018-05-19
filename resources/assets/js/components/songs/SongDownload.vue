<template>
    <div class="songview">
        <div class="row">
            <div class="col-md-8">
                <div v-for="song in songs" class="panel panel-default">
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
                            {{ song.song_description }}
                       
                        </div>
                    </div>

                <div class="panel-footer">
                    <a class="btn btn-success btn-lg" :href="song.src" :download="song.title" > Download </a>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import Aplayer from 'vue-aplayer';

export default {

    components: { Aplayer },

    props: ['song_id'],
    
    beforeMount() {
        this.getPrivateSong();
    },
    
    mounted() {
        console.log('song downn Component mounted.');
    },

    methods: {
        getPrivateSong() {
            axios.get('/artist/songs/' + this.song_id).then(response => {
                console.log((response.data))
                if(response.data !='') { 
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
                description: '',
                image: ''
            }
            ],
        }
    }
}
    
</script>