<template>
    <div>
        <a class="btn btn-sm btn-default" data-toggle="modal" data-target="#FavouriteModal">
            favourite: {{ favourite.length }}
        </a>
        <!-- Modal for all favourite artist s or fans view -->
        <div class="modal" id="FavouriteModal" tabindex="-1" role="dialog" aria-labelledby="FavouriteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="FavouriteModalLabel">All favourite ( {{ favourite.length }} )</h5>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-for="favourite in show_favourite" class="well">
                            <div class="row">
                                <img :src="favourite.followed.avatar" width="30px" height="30px">
                                {{ favourite.followed.name }} <a class="btn btn-primary btn-sm pull-right" :href="location+favourite.followed.slug">view profile</a>
                            </div>
                        </div>  
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-if="!no_data" type="button" class="btn btn-primary" @click="morefavourite">show more</button>
                        <button v-else="no_data" type="button" class="btn btn-primary">no more..</button>
                    </div>
                </div>
            </div>
        </div><!-- end of modal -->
    </div>
</template>

<script>
    export default {

        mounted() {
            console.log('favourite Component mounted.');
            this.getAllFavourite();
        },

        watch: {
            count() {   
                this.show_favourite = this.favourite.slice(0,this.count);
                if(this.favourite.length < this.count) {
                    this.no_data = true;
                }
            }
        },

        methods: {
            getAllFavourite() {
                axios.get('/get-all-favourite/').then(response => {
                    console.log(response.data.length);
                    if(response.data.length > 0) { 
                        this.favourite = response.data;
                        this.show_favourite = this.favourite.slice(0,5);//total 5 starts from index 0
                    } else {
                        this.no_data = true;
                    }
                }).catch(error => {
                    console.log(error);
                });
            },

            morefavourite() {
                this.count = this.count + 5;
                
            }


        },

        data() {
            return {
                favourite:{},
                show_favourite:{},
                count:5,
                no_data:false,
                location:'http://localhost:8000/profile/'
            }
        }
    }
</script>

<style scoped> 
    .well{
        height: 58px;
        margin-bottom:0px;
        padding:20px;
    }

    .modal-body{
        height: 400px;
        overflow-y: auto;
    }

</style>
