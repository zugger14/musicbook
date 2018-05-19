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
                        <h5 class="modal-title" id="FriendsModalLabel">All friends</h5>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <div v-for="friend in show_friends" class="well">
                                <div class="row">
                                    {{ friend.name }} <a class="btn btn-primary btn-md" :href="friend.slug">view profile</a>
                                </div>
                            </div>  
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="moreFriends">show more</button>
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
                //return this.show_friends;
            }
        },

        methods: {
            getAllFriends() {
                axios.get('/getfriends/' + this.user_id).then(response => {
                    console.log(response.data);
                    if(response.data !='') { 
                        this.friends = response.data;
                        this.show_friends = this.friends.slice(0,1);//total 1 starts from index 0
                    } 
                }).catch(error => {
                    console.log(error);
                });
            },

            moreFriends() {
                this.count = this.count + 1;
                
            }
        },

        data() {
            return {
                friends:{},
                show_friends:{},
                count:1
            }
        }
    }
</script>
