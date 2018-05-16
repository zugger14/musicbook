<template>
    <div class="alert alert-info  notification-item">
        <a :href="url" class="alert-link">
            {{ read.data.name }} {{ read.data.message }}
           
        </a>
    </div>

</template>

<script>
    export default {
        props:['read'],

        data() {
            return {
                url:''
            }
        },

        mounted() {
            this.url = this.read.data.profile;        
        }
    }
</script>
