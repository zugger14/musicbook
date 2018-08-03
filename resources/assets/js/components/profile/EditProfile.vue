<template>
    <div>
        <a class="btn btn-md btn-primary" data-toggle="modal" data-target="#ProfileModal">
            Edit Profile
        </a>
        <!-- Modal for User Profile update -->
        <div class="modal fade " id="ProfileModal" tabindex="-1" role="dialog" aria-labelledby="ProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-profile" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ProfileModalLabel">{{ user.name }}'s Profile</h5>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                           <!-- <img :src="user.avatar" width="200px" height="200px">  i ondt know why tostring is needed as same like ssing from blade to vue we need json_encode      -->
                                           <change-profile-pic :avatar="user.avatar.toString()"></change-profile-pic>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label> Full Name</label>
                                            <input type="text" class="form-control" v-model="user.name">
                                        </div>
                                        <div class="form-group">
                                            <label> User Name</label>
                                            <input type="text" class="form-control" v-model="user.slug">
                                        </div>
                                        </div><div class="form-group">
                                            <label> Email</label>
                                            <input type="text" class="form-control" v-model="user.email">
                                        </div>
                                        <div class="form-group">
                                            <label> Location</label>
                                            <input type="text" class="form-control" v-model="user.profile.location">
                                        </div>

                                        <div class="form-group">
                                            <label> About Me</label>
                                          <input type="text" class="form-control" v-model="user.profile.about">
                                        </div>
                                </div> 

                                <div class="row">
                                    
                                </div> 
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="updateProfile">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of modal for profile update -->

    </div>

</template>

<script>

    export default {

        props: ['slug'],

        mounted() {

            this.getUserProfile();
        },

        methods: {
            getUserProfile() {
                axios.get('/getprofile/' + this.pslug).then( response =>{
                    //console.log('hey ' + response.data);
                    if(response.data != '') {
                        this.user = response.data;
                    }
                }).catch(error =>{
                    console.log(error);
                });
            },

            updateProfile() {
                this.user.profile.name = this.user.name;
                this.user.profile.slug = this.user.slug;
                this.user.profile.email = this.user.email;
                this.user.profile.id = this.user.id;

                 axios.put('/profile/update', this.user.profile).then( response =>{
                    if(response.data != '') {
                        this.$refs.closemodal.click();
                        setTimeout(()=>{
                            //window.location.href = this.profile_url + this.user.slug;//for giving time to see success message
                            window.location.reload();
                        },1000);
                        toastr.success(response.data)
                    }
                }).catch(error =>{
                    console.log(error);
                });
            }
        },

        data() {
            return {
                user: {profile: { } },
                pslug: this.slug,
                profile_url:'http://localhost:8000/profile/'
            }

        }
    }

</script>

<style scoped>
.modal-profile {
    width: 800px;
}
</style>