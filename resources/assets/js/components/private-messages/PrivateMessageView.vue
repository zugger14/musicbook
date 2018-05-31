<template>
    <div class="panel panel-default">
        <div class="panel-heading"> View Message</div>
        <div class="panel-body">
            <table class="table table-striped table-hover table-bordered table-condensed message-table">
                <tbody>
                    <td>sent to : {{ message.receiver.name }}</td>
                        <td class="col-sm-3">sent by :{{ message.sender.name }}</td>
                        <td class="com-sm-7">
                            {{ message.message }}
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

    props: [],

    mounted() {
        console.log('Component mounted.')
        this.$store.dispatch('setUserMessageR');//to load new messages after view for marking notification as read for viewd message else can put thismethod i=on parent componet that is sidebar component
 
    },

    computed: {
        message() {
            return this.$store.getters.getMessageView;
        }

    },


    methods :{


    }
}
</script>
