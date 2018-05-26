<template>
    <div class="panel panel-default">
        <div class="panel-heading">Private Messages Sent</div>
        <div class="panel-body">
            <table class="table table-striped table-hover table-bordered table-condensed message-table">
                <tbody>
                    <tr v-for="message in messagesS">
                        <td class="col-sm-3">{{ message.receiver.name }}</td>
                        <td class="com-sm-7">
                            <a href="#" @click.prevent="getMessageId(message)">
                                {{ message.subject }}
                            </a>
                        </td>
                        <td class="col-sm-2">
                            {{ message.created_at }}

                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>

<script>

export default {

    mounted() {
        console.log('Component mounted.')
        this.$store.dispatch('setUserMessageS');
    },


    computed: {
        messagesS() {
            return this.$store.getters.getMessagesS;
        }

    },


    methods :{
        
        getMessageId(message) {
            this.$store.dispatch('getPrivateMessageById', message.id);
            this.$emit('viewmess', message);
        }

    }
}
</script>
