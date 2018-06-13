<template>
    <div>
        <a class="btn btn-default" v-if="!added">
            <span title="add to favoutite" class="glyphicon glyphicon-plus"  @click="addToFavourite">fav</span>
        </a>
        <a class="btn btn-default" v-else>
            <span title="add to favoutite" @click="removeFavourite">remove fav</span>
        </a>
    </div>
</template>

<script>
export default {

    props: ['user'],

    mounted() {
        console.log('favourite Component mounted.');
        this.getAllFavourite();
        this.authUserData();
    },  

    computed: {
        added() {
            let val = false;
            this.favourite.forEach((favo) => {
                if (favo.followed_id == this.user.id ) {
                    val = true;
                }
            }); 
            return val;
        },
    },


    methods: {

        authUserData() {
            axios.get('/auth_user_data').then(response => {
                this.authUser = response.data;
            });
        },

        addToFavourite() {
            axios.get('/add-favourite/' + this.user.id).then(response =>{
                setTimeout(function() {
                    location.reload();
                },1000);
                toastr.success(response.data.message);
            }).catch(error=>{
                console.log(error);
            });   
        },

        getAllFavourite() {
            axios.get('/get-all-favourite').then(response =>{
                //console.log(JSON.stringify(response.data));
                this.favourite = response.data;
            }).catch(error=>{
                console.log(error);
            });   
        },

        removeFavourite() {
            axios.get('/remove-favourite/' + this.user.id).then(response =>{
                setTimeout(function() {
                    location.reload();
                },1000);
                toastr.success(response.data.message);

            }).catch(error=>{
                console.log(error);
            });   
        }
    },

    data() {
        return {
            favourite:[],
            authUser:{}
        }
    }
}
</script>
