<template>
    <li class="dropdown">
    <a href="#" id="message-tab" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-envelope"></span><span class="badge" id="count">{{ unreadmessages.length }}</span></a>
    <ul class="dropdown-menu text-center"  id="messages">
        <li v-for="message in messages.slice(0,5)">
            <a href="/inbox" :class="[message.read == 0 ? 'unread' : 'read']" > {{ message.sender.name }} sent you a new message go ahead and open it so you kn wht istt <p class="small pull-right">{{ message.created_at }}</p></a>
        </li>
        <li>
            <a href="/inbox">See All Messages</a>
        </li>
    </ul>
</li></template>

<script>
export default {

    props: ['user_id'],

    mounted() {
        console.log('Component mounted.');
        this.$store.dispatch('setUserMessageR');

    },

    computed: {

        messages() {
            return this.$store.getters.getMessagesR;
        },

        unreadmessages() {
            let unreadmessages = [];
            this.messages.forEach( (message) =>{
                if(message.read == 0) {
                    unreadmessages.push(message);
                }
            })
            return unreadmessages;
        }

    },

    sockets: {
        message(data) {
            let message = JSON.parse(data);
            console.log('socket' + message.receiver_id);
            if(message.receiver_id == this.user_id) {
                this.$store.dispatch('newMessageNotification', message);
            }
        }
}

}

</script>

<style scoped>
    li ul li{
        min-width: 400px;

    }
    .unread {
        font-weight: bold;
    }

</style>
