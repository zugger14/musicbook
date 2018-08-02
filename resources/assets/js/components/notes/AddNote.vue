<template>
    <div>

        <!-- delte confirm modal -->
        <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="DeleteModalLabel">Delete Note</h5>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                      are you sure to delete Note?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" @click="deleteNote" >Confirm delete</button>
                    </div>
                </div>
            </div>
        </div><!-- end of modal -->



        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><a class="btn btn-primary btn-md" @click.prevent="addMode = true">Add Note</a></div>

                    <div class="panel-body" v-show="addMode">
                        <div class="form-group">
                            <label>title</label>
                            <input type="text" v-model="newnotes.title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>content</label>
                            <editor v-model="newnotes.content"></editor>
                        </div>
                        <div class="form-group">
                            <label>private</label>
                            <input type="checkbox" v-model="newnotes.private">
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-success btn-sm" @click="addNote">save</button>
                            <button class="btn btn-default btn-sm" @click="addMode=false">cancel</button>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default" v-if="noteExists" v-for="note in show_notes">
                    <div class="panel-heading">{{ note.title }}<span class="glyphicon glyphicon-pencil" @click="()=>{editMode = true;clicked = note.id;}"></span> 
                        <input type="text" v-model=note.title v-if="editMode && clicked == note.id">
                        <span class="glyphicon glyphicon-trash" data-target="#DeleteModal" data-toggle="modal" @click="storeNote(note)"></span>
                    </div>

                    <div class="panel-body">
                        <div class="form-group">
                            <label>content</label>
                            <div v-html="note.content"></div>
                            <editor v-if="editMode && clicked == note.id" class="form-control" v-model="note.content"> </editor>
                        </div>
                        <div class="panel-footer" v-if="editMode && clicked == note.id ">
                            <button class="btn btn-success btn-sm" @click="editNote(note)"> save</button>
                            <button class="btn btn-default btn-sm" @click="cancel"> cancel</button>

                        </div>
                    </div>
                </div>
                <infinite-loading v-if="noteExists" @infinite="infiniteHandler"></infinite-loading>                 
            </div>
        </div>
    </div>
</template>

<script>

    import Editor from '@tinymce/tinymce-vue'

  //  var Editor = require('@tinymce/tinymce-vue')

    export default {

        props:['user_id'],


        mounted() {
            console.log('Component mounted.')
            this.showNotes();
        },

        components: { Editor } ,

        watch: {
            count() {
                this.show_notes = this.notes.slice(0,this.count);
                if(this.notes.length < this.count) {
                    this.no_data = true;
                }
            }
        },

        methods: {
            addNote() {

                let formdata = new FormData();
                formdata.append('title', this.newnotes.title);
               // this.newnotes.content = JSON.stringify(this.newnotes.content);
                console.log((this.newnotes.content));
                formdata.append('content', this.newnotes.content);
                formdata.append('private',this.newnotes.private);

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

            storeNote(n) {
                this.removedNote = n;
            },


            showNotes() {
                axios.get('/getnotes/' + this.user_id ).then(response =>{
                    this.noteExists = true;
                    this.notes = response.data;
                    this.show_notes = this.notes.slice(0,5);

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

            closeModal() {
                this.$refs.closemodal.click();
                
            },


            deleteNote() {
                axios.delete('/notes/' + this.removedNote.id).then(response =>{
                    console.log(response.data);
                    this.closeModal();
                    this.showNotes();
                }).catch(error =>{
                    console.log(error);
                });

            },

            cancel(){

                this.editMode = false;
                this.showNotes()
            },

            infiniteHandler($state) {
                setTimeout(() => {
                    this.moreFeeds();
                    if(this.no_data == true) {
                        $state.complete();
                    } else {
                        $state.loaded();
                    }

                }, 500);
            },

            moreFeeds() {
                this.count = this.count + 5 ;
                    
            }

        },

        data() {
            return {

                notes:{ 
                    title:'',
                    content:'',
                    private:''

                },

                newnotes:{ 
                    title:'',
                    content:'',
                    private:''

                },

                removedNote:'',

                addMode:false,
                noteExists:false,
                editMode:false,
                clicked:'',
                show_notes: {},
                no_data:false,
                count:5,


            }
        }
    }
</script>

<style> 
/* add scroll to bottom when new note added and for deleted it is fine coz of splice only deleted array will be gone without any change to page*/
    #mceu_31{
        visibility: hidden;
    }

    .mce-has-close.mce-in {
        visibility: hidden;

    }
</style>



