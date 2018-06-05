<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <ul class="nav nav-pills nav-stacked pm-sidebar">
                    <li><a :class="currentView == 'compose-message' ? 'active' :''" @click="currentView='compose-message'">compose message</a></li>
                    <li><a :class="currentView == 'private-message-inbox' ? 'active' : ''" @click="currentView='private-message-inbox'">Inbox</a></li>
                    <li><a :class="currentView == 'private-message-sent' ? 'active' : ''" @click="currentView='private-message-sent'">sent messages</a></li>
                </ul>
            </div>
            <div class="col-sm-10">
                <component v-on:viewmess="viewMess" :is="currentView"></component>
            </div>
        </div> 
    </div>
 </template>

<script>
    
    import PrivateMessageSent from './PrivateMessageSent'
    import PrivateMessageInbox from './PrivateMessageInbox'
    import ComposeMessage from './ComposeMessage'
    import PrivateMessageView from './PrivateMessageView'

    import VueSocketio from 'vue-socket.io'

    Vue.use(VueSocketio, 'http://localhost:8890');


    export default {

        props: ['user_id'],

        mounted() {
            console.log('Component mounted.')
        },

        components :{ PrivateMessageInbox, PrivateMessageSent, ComposeMessage, PrivateMessageView},

        data() {
            return {
                currentView: 'private-message-inbox',
              //  clickedMessage: ''
            }
        },

        methods: {
            viewMess(message) {
                this.currentView = 'private-message-view';
                //this.clickedMessage = message;
            }
        },

/*        sockets: {
            message(data) {
                let message = JSON.parse(data);
                console.log('socket' + message.receiver_id);
                if(message.receiver_id == this.user_id) {
                    this.$store.dispatch('newMessageNotification', message);
                }
            }

        }
*/


    }
</script>

<style>
    .pm-sidebar.nav-pills a.active {

        background-color:#2c8fbb;
        color:#fff;

    }
</style>
