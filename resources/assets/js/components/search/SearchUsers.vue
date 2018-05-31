<template>
   <form class="navbar-form" role="search">
    <div class="input-group" style="width: 100%">
        <input type="text" ref="textfield" class="form-control" style="border-radius:0px; " placeholder="Search Users" v-model="query">
        <div class="input-group-btn">
            <button class="btn btn-default" @click.prevent=""><span class="glyphicon glyphicon-search"></span></button>
        </div>
    </div>
    <li class="search" @click="showSearch=true">
        <ul class="well"  v-if="showSearch"><!-- add div element to display user info when hovered -->
            <li v-for="user in users" :key=user.id><a :href="'/profile/' + user.slug"><img class="pull-left" :src="user.avatar" width="30px" height="30px">{{  user.name }}</a></li>
        </ul>
        <ul v-if="!showSearch && this.query.length > 1">
            <li><a href="#">no any matches</a></li>
        </ul>
    </li>
</form>
</template>

<script>
export default {
    mounted() {
        console.log('Component mounted.')
    },

    watch: {
        query() {

            if(this.query.length > 0){
                axios.get('/searchusers/' + this.query).then(response => {
                    if(response.data == false){
                        this.showSearch = false;
                    }
                    if(response.data != false) {
                        this.showSearch = true;
                        this.users = response.data;
                        console.log(response.data);
                    }

                }).catch(error =>{
                    console.log(error);
                });
            }
            else {
              this.showSearch=false;

            }

        }

    },


    data() {
        return {
            query: '',
            users: {},
            showSearch:false,
            profile_url: '/profile/'
        }

    }
}
</script>

<style scoped>

    li ul {
        border-radius:0px;
        width:260px;
    }

    ul {

      list-style-type: none;
      padding: 0;
      margin: 0;
      position: absolute;
      z-index: 999;
    }
    

ul li a {
  border: 1px solid #ddd;
  margin-top: -1px; /* Prevent double borders */
  background-color: #f6f6f6;
  padding: 5px;
  text-decoration: none;
  font-size: 16px;
  display: block
}

ul li a:hover:not(.header) {
  background-color: #eee;
}


</style>
