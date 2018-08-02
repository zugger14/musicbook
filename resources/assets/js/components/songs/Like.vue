<template>
    <div>
        <p class="small" v-for="like in song.like">
            <img :src="like.user.avatar" width="25px" height="25px">
        </p>
        <button class="btn btn-d
        +-efault btn-sm" v-if="!authUserLikedPost" @click="like">
            like
        </button>

        <button class="btn btn-primary btn-sm" v-else="authUserLikedPost"  @click="unLike">
            unlike
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

            like() {
                axios.get('/like/' +this.id ).then(response => {
                   // console.log((response.data))
                        this.song.like.push(response.data);
                        toastr.success('you have liked the song');//imported in parent class not ins cope here so i defined globally using windows.toastr which is pretty naive

                }).catch(error => {
                    console.log(error);
                });

            },

            unLike() {
                axios.get('/unlike/' +this.id ).then(response => {

                    var index = this.song.like.indexOf(response.data);//www.youtube.com/watch?v=Anham4RDlbU&list=PLZAiN3wmUtzV1eI7mAxERQaE2LkyA5Nh6&index=50    hee makes use of state if want to change look on this using vuex states
                    this.song.like.splice(index,1);//check this one i think wrong
                    toastr.success('you have unliked the song');

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

            likers() {

                var likers = [];

                this.song.like.forEach( (like) =>{
                    likers.push(like.user.id)
                })

                return likers;
            },

            authUserLikedPost() {

                var checkindex = this.likers.indexOf(
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

    .btn {
        border-radius: 50px;
    }



</style>
