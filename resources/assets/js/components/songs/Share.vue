<template>
    <div>
        <p class="small" v-for="share in song.share">
            <img :src="share.user.avatar" width="25px" height="25px">
        </p>
        <button class="btn btn-default btn-sm" v-if="!authUserSharedPost" @click="share">
            share
        </button>

        <button class="btn btn-primary btn-sm" v-else="authUserSharedPost"  @click="unShare">
            unshare
        </button>
    </div>
</template>

<script>
    export default {
        props: ['id','songs'],  //use vue state here so that songs data can be get from there without using props

        mounted() {
            console.log('likes Component mounted.');
            this.getAuthUserData();//change this place acsuses repeats many times 
        },

        methods: {
            getAuthUserData() {

                axios.get('/auth_user_data').then(response => {
                    //console.log((response.data))
                    if(response.data !='') { 
                        this.user = response.data;
                        
                    }
                }).catch(error => {
                        console.log(error);
                });
            },

            share() {
                axios.get('/share/' +this.id ).then(response => {
                    // console.log((response.data))
                    if(response.data == 0) {
                        console.log('you have already shared this song.you need to delete previous share to share again.');
                    } else {
                        this.song.share.push(response.data);
                        toastr.success('you have shared the song');
                    }
                }).catch(error => {
                    console.log(error);
                });
            },

            unShare() {
                axios.get('/unshare/' +this.id ).then(response => {

                    var index = this.song.share.indexOf(response.data);
                    this.song.share.splice(index,1);//--when same song appers twice splice not working for unshare.
                    toastr.success('you have unshared the song');
                    location.reload();

                }).catch(error => {
                    console.log(error);
                });

            }



        },

        data() {

            return {
                user:''
            }
        },

        computed: { //automatically called when data dependency changes like below sif song changes or like changes recomputes
            song() {

                return this.songs.find( (song) =>{  //get a single song from array of songs for displaying likes for it..

                    return song.id === this.id
                })
            },

            sharers() {

                var sharers = [];

                this.song.share.forEach( (share) =>{
                    sharers.push(share.user.id)
                })

                return sharers;
            },

            authUserSharedPost() {

                var checkindex = this.sharers.indexOf(
                    this.user.id
                )

                if(checkindex === -1) {
                    return false;
                }
                return true;
            }

        }
    }
</script>

<style scoped>
    
    img {
        border-radius:50%;
    }

</style>
