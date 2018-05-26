<template>
    <div class="panel panel-default">
        <div class="panel-heading">Compose Message</div>
        <div class="panel-body">
            <div class="form-group">
                            <div class="form-group">
                                <label for="genre"> user's name</label>
                                <v-select :placeholder="'choose user'" v-model="message.receiver" :label="name" :options="users"></v-select>
                            </div>
                            <div class="form-group">
                                <label for="title">Subject:</label>
                                <input type="text" v-model="message.subject" class="form-control" required maxlength="255">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Message:</label>
                                <textarea class="form-control" id="message-text" v-model="message.message"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success btn-md" @click="sendMessage"> send </button>                             
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</template>

<script>
    
import vSelect from 'vue-select'

export default {

    mounted() {
        console.log('Component mounted.')
        this.$store.dispatch('getUsersList');
    },

    components: {vSelect},

    computed: {

        users(){
            return this.$store.getters.getUsersList;//get only those users who are friends with logged in user
        }

    },

    data() {
        return {
            message: {
                subject:'',
                message:'',
                receiver:''

            },
            name:'name'
        }
    },

    methods :{
        sendMessage() {
            this.$store.dispatch('sendPrivateMessage', this.message);

        }
    }
}
</script>
