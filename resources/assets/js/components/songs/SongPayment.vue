<template>
	<div> <!-- /artist/songs/buy -->
		<a href="" class="btn btn-md btn-default" id="custom-file-upload" data-toggle="modal" data-target="#paymentModal" @click="showSongPayModal">Buy for Nrs 100</a>

		<!-- Modal for song payment information before proceeding to paypal payment-->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Song Payment Information</h5>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close" @click="resetForm">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form ref="uploadform">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <div id="image_previews">
                                                <img ref='image' class="" v-bind:src="song.imgpreview" width="200px" height="200px" >
                                                <input class="form-control-file" ref="imageinput" type="file" name="feature_image" @change="showImage($event)">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="title">Song Title:</label>
                                                <input type="text"  v-model="song.title" class="form-control" required maxlength="255">
                                            </div>
                                            <div class="form-group">

                                            </div>
                                            <div class="form-group">
                                                <label for="upload_type">Payment Amount</label>
                                                <select name="upload_type" v-model="song.upload_type" class="form-control">
                                                    <option value="public">public( free )</option>
                                                    <option value="private">private( for sale )</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="form-group">
                                <label for="info" class="col-form-label">We accept payment from either paypal or you can use cards as well </label>
                                
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="resetForm" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="uploadSong(song)">proceed to payment</button>
                    </div>
                </div>
            </div>
        </div><!-- end of modal -->
	</div>

</template>

<script>
export default {

    props: ['song'],

	mounted() {
		console.log('Component mounted.')
	},

    data() {
	    return {
	        song: this.song
	    }
   	}
}
</script>

