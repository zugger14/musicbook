<template>
    <div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><a class="btn btn-primary btn-md" @click.prevent="addMode = true">Add Note</a></div>

                    <div class="panel-body" v-if="addMode">
                        <div class="form-group">
                            <label>title</label>
                            <input type="text" v-model="notes.title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>content</label>
                            <textarea v-model="notes.content" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>private</label>
                            <input type="checkbox" v-model="notes.private">
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-success btn-sm" @click="addNote">save</button>
                            <button class="btn btn-default btn-sm" @click="addMode=false">cancel</button>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default" v-if="noteExists" v-for="note in notes">
                    <div class="panel-heading">{{ note.title }}<span class="glyphicon glyphicon-pencil" @click="editMode = true"></span> 
                        <input type="text" v-model=note.title v-if="editMode">
                        <span class="glyphicon glyphicon-trash" @click="deleteNote(note)"></span>
                    </div>

                    <div class="panel-body">
                        <div class="form-group">
                            <label>content</label>
                            <textarea class="form-control">{{ note.content }}</textarea>
                        </div>
                        <div class="panel-footer" v-if="editMode">
                            <button class="btn btn-success btn-sm" @click="editNote(note)"> save</button>
                            <button class="btn btn-default btn-sm" @click="cancel"> cancel</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props:['user_id'],

        mounted() {
            console.log('Component mounted.')
            this.showNotes();
        },

        methods: {
            addNote() {

                let formdata = new FormData();
                formdata.append('title', this.notes.title);
                formdata.append('content', this.notes.content);
                formdata.append('private',this.notes.private);

                axios.post('/notes/',formdata,{
                    headers: {//no need to put headers here dont know why it works with multipart-fromdata??
                        'Content-Type': 'multipart/form-data'
                    }
                } ).then(response =>{
                    console.log(response.data);
                    this.addMode = false;
                    this.showNotes();
                }).catch(error =>{
                    console.log(error);
                });
            },

            showNotes() {
                axios.get('/getnotes/' + this.user_id ).then(response =>{
                    this.noteExists = true;
                    this.notes = response.data;
                    console.log(response.data);
                }).catch(error =>{
                    console.log(error);
                });
            },

            editNote(note) {
                axios.put('/notes/' + note.id, note).then(response =>{
                    console.log(response.data);
                    this.editMode = false;
                }).catch(error =>{
                    console.log(error);
                });

            },

            deleteNote(note) {
                axios.delete('/notes/' + note.id).then(response =>{
                    console.log(response.data);
                    this.showNotes();
                }).catch(error =>{
                    console.log(error);
                });

            },

            cancel(){

                this.editMode = false;
                this.showNotes()
            }

        },

        data() {
            return {

                notes:{ title:'',
                content:'',
                private:''

                },

                addMode:false,
                noteExists:false,
                editMode:false
            }
        }
    }
</script>



