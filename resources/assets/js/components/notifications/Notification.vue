<template>
    <li class="dropdown" @click="markNotificationsAsRead">
        <a href="#" id="notification-tab" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-bell-o" aria-hidden="true"></span><span class="badge" id="count">{{ unreadNotifications.length }}</span></a>
        <ul class="dropdown-menu text-center"  id="notification">
            <li>
                <notification-item v-for="unread in unreadNotifications" :unread="unread"></notification-item>
                <oldnotification-item v-for="read in readNotifications.slice(0,7)" :read="read"></oldnotification-item>  
            </li>
            <li>
                <a href="/notifications">See All Notifications</a>
            </li>
        </ul>
    </li>
</template>

<script>

    import NotificationItem from './NotificationItem.vue';
    import OldnotificationItem from './OldnotificationItem.vue';

    export default {

        props:['unreads', 'reads', 'id'],

        components:{NotificationItem, OldnotificationItem},

        mounted() {
            //console.log(JSON.stringify(this.readNotifications));
            this.listen();
        },

        data() {
            return {

                unreadNotifications: this.unreads,
                readNotifications:this.reads
            }
        },

        methods:{
            markNotificationsAsRead(){
                if(this.unreadNotifications.length){
                    axios.get('/markAsRead');
                }
            },

            listen() {
                Echo.private('App.User.' + this.id)
                .notification( (notification) => {
                    console.log(notification.type);
                    //toastr.success("working");
                    document.getElementById('noty_audio').play();
                    toastr.success(notification.name + notification.message);
                    let newunreadNotifications = { data:notification } 
                    this.unreadNotifications.push(newunreadNotifications);
                });
            }
        },
    }

</script>

<style scoped>
    li ul
    {
       width: 500px;
       padding:0px; 
    }

    .alert {
        padding: 5px;
        margin-bottom:0px;
    }

</style>
