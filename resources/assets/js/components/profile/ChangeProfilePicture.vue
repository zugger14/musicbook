<template>
    <div class="text-center">
        <img :src="uavatar" width="140px" height="140px" style="border-radius: 50%;">
        <input ref="imginput" type="file" @change="imgPreview">

        <p v-if="!newimg" ><a class="btn btn-default btn-sm" @click="selectFile">change profile picture </a></p>

        <p class="text-center" v-else><a class="btn btn-success btn-sm" @click="changePic"> save picture </a></p>

    </div>

</template>

<script>

    export default {

        props: ['avatar'],

        mounted() {
            //console.log('change profile picture Component mounted.')
            //console.log(this.uavatar);
        },

        watch: {

            uavatar() {
                this.newimg=true
            }

        },

        methods: {
            changePic() {

                let formData = new FormData();
                formData.append('avatar', this.imgfile);

                axios.post('/changeprofilepic', formData ).then(response => {
                    if(response.data != '') {
                        toastr.success('succesfully saved your new profile picture.')
                        this.newimg = false;                    }

                }).catch(error=>{
                    console.log(error);
                });

            },

            imgPreview(event) {
                this.imgfile = event.target.files[0];
                this.uavatar = URL.createObjectURL(event.target.files[0]);
            },

            selectFile() {
                this.$refs.imginput.click();
            }
        },

        data() {
            return {
                uavatar: this.avatar,
                imgfile: '',
                newimg:false
            }

        }
    }
</script>

<style scoped>

input[type="file"] {
    display: none;
}

</style>