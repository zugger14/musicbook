<template>
	<div> 
		<!-- Modal for song payment information before proceeding to paypal payment-->
        <div class="modal fade" id="paypalModal" tabindex="-1" role="dialog" aria-labelledby="paypalModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paypalModalLabel">Song Payment Information</h5>
                        <button type="button" class="close" ref="closemodal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form ref="uploadform">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        Now paypal login page will be loaded in a  new window..you can use your paypal  credenials or you can also use credit cards.
                                    </div>
                                </div>  
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="paypalPayment">proceed to paypal</button>
                    </div>
                </div>
            </div>
        </div><!-- end of modal -->
	</div>

</template>

<script>
export default {

    props: ['approval_url'],

	mounted() {
        $('#paypalModal').modal('show');
		console.log('Component mounted.')
	},

    methods: {

        paypalPayment() {
            this.$refs.closemodal.click();//$('#paypalModal').modal('show') doesnot work here why?
            window.open(this.approval_url, "_blank","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=600,height=600");
          //  location.reload();
        }
    }
}
</script>

