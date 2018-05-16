<template>
<div class="alert alert-success notification-item">
    <a :href="url" class="alert-link">
        {{ unread.data.name }} {{ unread.data.message }}    <!--actually broadcast send notification.name but from laravel notifications send in this formmat soo  -->
        
    </a>
</div>
</template>

<script>
    export default {
        props:['unread'],

        data() {
            return {
                url:''
            }
        },

        mounted() {
            this.url = this.unread.data.profile;        
        }
    }
</script>
