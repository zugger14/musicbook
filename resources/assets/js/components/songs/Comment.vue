<template>
<div class="container-fluid">
    <br>
      <input type="text" v-model="newcomment.comment" class="form-control" placeholder=" write a comment.." @keydown.enter="addComment">
    <div class="row">
    <!-- Contenedor Principal -->
    <div class="comments-container">
        <ul id="comments-list" class="comments-list">
            <li v-for="comment in show_comments" :key="comment.id">
                <div class="comment-main-level">
                    <!-- Avatar -->
                    <div class="comment-avatar"><img :src="comment.user.avatar" alt=""></div>
                    <!-- Contenedor del Comentario -->
                    <div class="comment-box">
                        <div class="comment-head">
                            <h6 class="comment-name by-author"><a href="http://creaticode.com/blog">{{ comment.user.name }}</a></h6>
                            <span>{{ comment.created_at }}</span>
                            <i class="fa fa-trash" v-if="comment.user.id == user.id" @click="deleteComment(comment)"></i>
                            <i class="fa fa-pencil" v-if="comment.user.id == user.id" @click="edit = comment.id"></i>
                            <i class="fa fa-close" v-if="edit == comment.id" @click="reset(comment)"></i>                  
                        </div>
                        <div class="comment-content" v-if="edit != comment.id">
                            {{ comment.comment }}                        
                        </div>
                        <input v-if="edit == comment.id" type="text" v-model="comment.comment" class="form-control" placeholder=" your new comment.." @keydown.enter="editComment(comment)">
                    </div>
                </div>
            </li>
                <button v-if="!no_data" type="button" class="btn btn-primary" @click="moreComments">show more</button>
                <button v-else="no_data" type="button" class="btn btn-primary">no more comments</button>
        </ul>
    </div>
    </div>
</div>
</template>

<script>
    export default {
        props: ['song'],

        mounted() {
            console.log('comments Component mounted.');
            this.getComments();
            this.getAuthUserData();

        },

        watch: {
            count() {
                this.show_comments = this.comments.slice(0,this.count);
                if(this.comments.length < this.count) {
                    this.no_data = true;
                }
            }
        },

        methods: {

            reset(comment) {
                this.getComments();
                this.edit = false;
            },

            addComment() {
                axios.post('/comment', this.newcomment).then(response => {
                    this.comments.push(response.data);
                    this.show_comments.push(response.data);
                    this.newcomment.comment = '';
                }).catch(error => {
                    console.log(error);
                });

            },

            editComment(comment) {
                axios.put('/comment/' + comment.id, comment).then(response => {
                    toastr.success('edited your comment');
                    this.edit = false;
                }).catch(error => {
                    console.log(error);
                });

            },

            getComments() {
                axios.get('/comment/' + this.song.id).then(response => {
                    this.comments = response.data;
                    this.show_comments = this.comments.slice(0,5);
                });
            },

            deleteComment(comment) {
                if(confirm('are you sure to delete this comment ?')) {
                    axios.delete('/comment/' + comment.id).then(response => {
                        let index = this.show_comments.indexOf(comment);
                        let index_in_all = this.comments.indexOf(comment);

                        this.show_comments.splice(index, 1);
                        this.comments.splice(index_in_all, 1);

                    });
                }
            },

            getAuthUserData() {
                axios.get('/auth_user_data').then(response => {
                    if(response.data !='') { 
                        this.user = response.data;
                    }
                }).catch(error => {
                        console.log(error);
                });
            },


            moreComments() {
                this.count = this.count + 5;
                
            }

        },

        data() {

            return {
                newcomment: {song_id: this.song.id, comment: '', user: {}},
                comments: [],
                user: {},
                edit: false,
                show_comments:{},
                count:5,
                no_data:false               
            }
        },


    }
</script>

<style scoped>
    /*
 * Oscuro: #283035
 * Azul: #03658c
 * Detalle: #c7cacb
 * Fondo: #dee1e3
 ----------------------------------*/
 * {
     margin: 0;
    padding: 0;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
 }

 a {
    color: #03658c;
    text-decoration: none;
 }

ul {
    list-style-type: none;
}

body {
    font-family: 'Roboto', Arial, Helvetica, Sans-serif, Verdana;
    background: #dee1e3;
}

/** ====================
 * Lista de Comentarios
 =======================*/
.comments-container {
    //margin: 60px auto 15px;
    width: 768px;
}

.comments-container h1 {
    font-size: 36px;
    color: #283035;
    font-weight: 400;
}

.comments-container h1 a {
    font-size: 18px;
    font-weight: 700;
}

.comments-list {
    margin-top: 30px;
    position: relative;
}

/**
 * Lineas / Detalles
 -----------------------*/
.comments-list:before {
    content: '';
    width: 2px;
    height: 100%;
    background: #c7cacb;
    position: absolute;
    left: 32px;
    top: 0;
}

.comments-list:after {
    content: '';
    position: absolute;
    background: #c7cacb;
    bottom: 0;
    left: 27px;
    width: 7px;
    height: 7px;
    border: 3px solid #dee1e3;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
}

.comments-list li {
    margin-bottom: 15px;
    display: block;
    position: relative;
}

.comments-list li:after {
    content: '';
    display: block;
    clear: both;
    height: 0;
    width: 0;
}

/**
 * Avatar
 ---------------------------*/
.comments-list .comment-avatar {
    width: 65px;
    height: 65px;
    position: relative;
    z-index: 99;
    float: left;
    border: 3px solid #FFF;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.2);
    -moz-box-shadow: 0 1px 2px rgba(0,0,0,0.2);
    box-shadow: 0 1px 2px rgba(0,0,0,0.2);
    overflow: hidden;
}

.comments-list .comment-avatar img {
    width: 100%;
    height: 100%;
}

.comment-main-level:after {
    content: '';
    width: 0;
    height: 0;
    display: block;
    clear: both;
}
/**
 * Caja del Comentario
 ---------------------------*/
.comments-list .comment-box {
    width: 680px;
    float: right;
    position: relative;
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.15);
    -moz-box-shadow: 0 1px 1px rgba(0,0,0,0.15);
    box-shadow: 0 1px 1px rgba(0,0,0,0.15);
}

.comments-list .comment-box:before, .comments-list .comment-box:after {
    content: '';
    height: 0;
    width: 0;
    position: absolute;
    display: block;
    border-width: 10px 12px 10px 0;
    border-style: solid;
    border-color: transparent #FCFCFC;
    top: 8px;
    left: -11px;
}

.comments-list .comment-box:before {
    border-width: 11px 13px 11px 0;
    border-color: transparent rgba(0,0,0,0.05);
    left: -12px;
}

.comment-box .comment-head {
    background: #FCFCFC;
    padding: 10px 12px;
    border-bottom: 1px solid #E5E5E5;
    overflow: hidden;
    -webkit-border-radius: 4px 4px 0 0;
    -moz-border-radius: 4px 4px 0 0;
    border-radius: 4px 4px 0 0;
}

.comment-box .comment-head i {
    float: right;
    margin-left: 14px;
    position: relative;
    top: 2px;
    color: #A6A6A6;
    cursor: pointer;
    -webkit-transition: color 0.3s ease;
    -o-transition: color 0.3s ease;
    transition: color 0.3s ease;
}

.comment-box .comment-head i:hover {
    color: #03658c;
}

.comment-box .comment-name {
    color: #283035;
    font-size: 14px;
    font-weight: 700;
    float: left;
    margin-right: 10px;
}

.comment-box .comment-name a {
    color: #283035;
}

.comment-box .comment-head span {
    float: left;
    color: #999;
    font-size: 13px;
    position: relative;
    top: 1px;
}

.comment-box .comment-content {
    background: #FFF;
    padding: 12px;
    font-size: 15px;
    color: #595959;
    -webkit-border-radius: 0 0 4px 4px;
    -moz-border-radius: 0 0 4px 4px;
    border-radius: 0 0 4px 4px;
}

.comment-box .comment-name.by-author, .comment-box .comment-name.by-author a {color: #03658c;}
.comment-box .comment-name.by-author:after {
   // content: '';
    background: #03658c;
    color: #FFF;
    font-size: 12px;
    padding: 3px 5px;
    font-weight: 700;
    margin-left: 10px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
}

/** =====================
 * Responsive
 ========================*/
@media only screen and (max-width: 766px) {
    .comments-container {
        width: 480px;
    }

    .comments-list .comment-box {
        width: 390px;
    }
}

</style>
