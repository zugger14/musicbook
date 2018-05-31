<template>
    <div class="container-fluid">
        <div class="row">
           <p class="text-center" v-if="loading" > loading... </p>
           <button class="btn btn-default btn-sm" v-if="status == 0" @click="addFriend">Add Friend</button>
           <button class="btn btn-default btn-sm" v-if="status == 'pending' " @click="acceptFriend">Accept Friend</button>
           <span class="text-success" v-if="status == 'waiting' ">Waiting For response</span>
           <div class="dropdown" v-if="status == 'friends' ">
                <button class="btn btn-default btn-sm  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Friends <span class="caret"></span></button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a href="" @click.prevent="removeFriend">remove as friend</a></li>
                </ul>
                <button class="btn btn-default btn-sm">Send Message</button>
            </div>

          <!--  <button class="btn btn-default btn-sm ">Friends</button>-->
        </div>
    </div>
</template>

<script>
export default {

    props: ['profile_user_id'],

    mounted() {

        axios.get('/check_relationship_status/' + this.profile_user_id).then((response) => {
           // console.log( response.data );
            this.status = response.data.status;
            this.loading = false;
        }).catch(error => {
            console.log(error);
        });
    },

    methods: {
        addFriend() {
            this.loading = true;
            axios.get('/addfriend/' + this.profile_user_id).then((response) => {
                console.log( response.data );
                if(response.data == true) {
                //  console.log( response.data );

                    this.status = 'waiting';
                }
                this.loading = false;
            
            }).catch(error => {
                console.log(error);
            });
        },

        acceptFriend() {
            this.loading = true;
            axios.get('/acceptfriend/' + this.profile_user_id).then((response) => {
              //  console.log( response.data );
                if(response.data === 1) {
               // console.log( response.data );

                    this.status = 'friends';
                }
                this.loading = false;
            
            }).catch(error => {
                console.log(error);
            });
        },

        removeFriend() {
            this.loading = true;
            axios.get('/removefriend/' + this.profile_user_id).then((response) => {
                if(response.data === 1) {
                    this.status = 0;
                    window.reload();
                }
                this.loading = false;
            
            }).catch(error => {
                console.log(error);
            });

        }


    },

    data() {
        return {

            status: '',
            loading: true

        }

    }
}
</script>
