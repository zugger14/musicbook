<template>
	<div> <!-- /artist/songs/buy -->
		<a href="" class="btn btn-md btn-default" data-toggle="modal" :data-target="'#paymentModal' + song.id ">Buy for Nrs 100</a>

		<!-- Modal for song payment information before before getting approval url to paypal payment-->
        <div class="modal fade" :id="'paymentModal' + song.id" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Song Payment Information</h5>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
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
                                                <img ref='image' class="" v-bind:src="songs.image" width="200px" height="200px" >
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="title">Song Title:</label>
                                                <input type="text"  v-model="songs.title" class="form-control" required maxlength="255">
                                            </div>
                                            <div class="form-group">

                                            </div>
                                            <div class="form-group">
                                                <label for="upload_type">Payment Amount</label>
                                                <input type="text"  v-model="songs.amount" class="form-control" required maxlength="255" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="form-group">
                                <label for="info" class="col-form-label">We accept payment from either paypal soon we are adding cards support as well </label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="confirmPay">proceed to payment</button>
                    </div>
                </div>
            </div>
        </div><!-- end of modal -->
        <paypal-payment v-if="showPaypal" :approval_url="paypal_approval_url"></paypal-payment>
	</div>

</template>

<script>
import PaypalPayment from './PaypalPayment.vue';

export default {

    props: ['song'],

    components :{PaypalPayment},

	mounted() {
		console.log('Component mounted.')
	},

    watch: {
        paypal_approval_url() {
           // this.resetForm();
            this.$refs.closemodal.click();
            this.showPaypal = true;
        }
    },

    methods: {
        resetForm(event) {
            this.songs = ''    
        },

        confirmPay() {//only works once then need to refresh the page because the props value is loaded on refresh once and i have reset the form before so resets songs value hence gives error of not initiakized data(songs)

            axios.post('/artist/songs/buy', this.songs).then(response => {
                if(response.data != '') {
                    axios.post('/payments/with-paypal', this.songs).then(response => {
                        console.log(JSON.stringify(response));
                        if(response.data !='') { 
                            this.paypal_approval_url = response.data.approval_url;
                           // this.payment_id = response.data.id;
                        } 
                    }).catch(error => {
                        console.log(error);
                    });
                }
                
            }).catch(error => {
                    console.log(error);
            });
        }
    },

    data() {
	    return {
	        songs: this.song,
            showPaypal:false,
            paypal_approval_url:''

	    }
   	}
}
</script>

