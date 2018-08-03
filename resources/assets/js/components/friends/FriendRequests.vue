<template>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-users"></i></span><span class="badge" id="count">{{ users.length }}</span></a>
            <ul v-if="requests" class="dropdown-menu">
                <li  v-for="user in users"><a href="" @click.prevent> {{ user.name }} sent  you friend request <button class="btn btn-sm btn-default" @click="confirmFriend(user)">confirm</button>  <button class="btn btn-sm btn-default" @click="removeRequest(user)">remove</button></a></li>
            </ul>

            <ul class="dropdown-menu" v-else="!requests">
                <li><a href="">no any friend requests</a></li>
            </ul>

      </li>
</template>

<script>
    export default {

        props: ['user_id'],

        updated() {
           this.getPendingRequests();//methods that runs after the computed properties have been all executed in this case this.users is accesible in this lifecycle hook but not in mounted
        },
        
        mounted() {
            this.$store.dispatch('setFriendsPending');
        },

        computed: {
            users() {
                return this.$store.getters.getFriendsPending;
            },

            /*
                requests() {  //esari gare ni huncha isntead of calling methods in updated lifecycle hooks
                    if( this.users.length > 0) {
                        return true
                    }
                    return false;
                }
            */
        },

        methods: {          
            getPendingRequests() {
    /*            store.js ma shifted ---- axios.get('/getpendingrequests').then((response) => {
                  //console.log(response.data);
                   if(response.data != '') {
                        this.requests = true;
                        this.users = response.data;
                   }
                
                }).catch(error => {
                    console.log(error);
                });*/
                if(this.users.length > 0 ) {
                    console.log('soo truee');
                    this.requests = true;
                } else {
                    this.requests = false;
                }
            },

            confirmFriend(user) {
                axios.get('/acceptfriend/' + user.id).then((response) => {
                    if(response.data == true) {
                        toastr.success('you are now friend with ' + user.name);
                        location.reload();
                    }
                
                }).catch(error => {
                    console.log(error);
                });

            },

            removeRequest(user) {
                axios.get('/removependingrequest/' + user.id).then((response) => {console.log(response.data);
                   if(response.data == 1) {
                // console.log(user.id);
                       var index = this.users.indexOf(user);//previous itried with user.id but not getting that..it was because whole reponse was returnde from backend instead return only id from there

                        this.users.splice(index,1);//balla working
                        if(this.users.length === 0) {
                            this.requests=false;
                        }

                        toastr.success('you have removed request sent from ' + user.name);
                        location.reload();
                   }
                
                }).catch(error => {
                    console.log(error);
                });
            }
        },

        data() {
            return {
                //users: this.user,
                requests: false
            }
        },

        sockets: {
            addfriend(data) {
                let user = JSON.parse(data);
                if(user.intended_userid == this.user_id) {
                    console.log(user);
                    this.$store.dispatch('newFriendRequest', user);
                    //this.$store.dispatch('setFriendsPending');
                }
            }
        }

    }
</script>

<style type="text/css" scoped>
    li ul {
        width: 500px;
        padding: 0px;

    }
</style>
