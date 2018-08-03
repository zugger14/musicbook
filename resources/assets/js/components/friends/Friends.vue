<template>
    <div>
        <a class="btn btn-sm btn-default" data-toggle="modal" data-target="#FriendsModal">
            Friends: {{ friends.length }}
        </a>
        <!-- Modal for all added friends view -->
        <div class="modal" id="FriendsModal" tabindex="-1" role="dialog" aria-labelledby="FriendsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="FriendsModalLabel">All friends ( {{ friends.length }} )</h5>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <div v-for="friend in show_friends" class="well">
                                <div class="row">
                                    <img :src="friend.avatar" width="30px" height="30px">
                                    {{ friend.name }} <a class="btn btn-primary btn-sm pull-right" :href="friend.slug">view profile</a>
                                </div>
                            </div>  
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-if="!no_data" type="button" class="btn btn-primary" @click="moreFriends">show more</button>
                        <button v-else="no_data" type="button" class="btn btn-primary">no more friends</button>
                    </div>
                </div>
            </div>
        </div><!-- end of modal -->
    </div>
</template>

<script>
    export default {

        props: ['user_id'],

        mounted() {
            console.log('friends Component mounted.');
            this.getAllFriends();
        },

        watch: {
            count() {
                this.show_friends = this.friends.slice(0,this.count);
                if(this.friends.length < this.count) {
                    this.no_data = true;
                }
            }
        },

        methods: {
            getAllFriends() {
                axios.get('/getfriends/' + this.user_id).then(response => {
                    console.log(response.data);
                    if(response.data !='') { 
                        this.friends = response.data;
                        this.show_friends = this.friends.slice(0,5);//total 5 starts from index 0
                    } else {
                        this.no_data = true;
                    }
                }).catch(error => {
                    console.log(error);
                });
            },

            moreFriends() {
                this.count = this.count + 5;
                
            }
        },

        data() {
            return {
                friends:{},
                show_friends:{},
                count:5,
                no_data:false
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
