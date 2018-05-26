<template>
    <div class="panel panel-default">
        <div class="panel-heading">Private Messages</div>
        <div class="panel-body">
            <table class="table table-striped table-hover table-bordered table-condensed message-table">
                <tbody>
                    <tr v-for="message in messagesR" :class="[message.read == 0 ? 'unread' : 'read']">
                        <td class="col-sm-3">{{ message.sender.name }}</td>
                        <td class="com-sm-7">
                            <a href="#" @click.prevent="getMessageId(message)">
                                {{ message.subject }}
                            </a>
                        </td>
                        <td class="col-sm-2">
                            {{ message.subject }}

                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

export default {

    mounted() {
        console.log('Component mounted.')
        this.$store.dispatch('setUserMessageR');
    },


    computed: {
        messagesR() {

            return this.$store.getters.getMessagesR;
        }

    },

    watch: {
        messages() {
         console.log(this.messages);
        }
    },

    methods :{
        getMessageId(message) {
            this.$store.dispatch('getPrivateMessageById', message.id);
            this.$emit('viewmess', message);
        }

    },

     data() {
        return {
                //messages: this.messagesR// computed property doesnot exist bedore data evaluation...so use computed prop in template directly no nned for this...
        }
    }
}
</script>

<style>
    .unread {
        font-weight: bold;
    }
</style>
