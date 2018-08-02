<template>
	<div class="progress">
	  <div class="progress-bar" role="progressbar" aria-valuenow="1"
	  aria-valuemin="0" aria-valuemax="100" :style="{width: value + '%'}">
	    {{ up }}%
	  </div>
	</div>	 
</template>

<script>
  export default {

  	mounted() {
  		console.log('mounted progress' + this.up);
  	},

  	props: ['value'],

    computed: {
      up() {
        return this.value;
      }
    },

  	data() {
  		return {
  			//up: this.value//only props works this data doesnot work may be cause of upload percentage is updated without refreshing components or only once mounted and data is also set only once ...can also put in computed so data is updated on value variable  change...but actually it is just okay to use props value because no calculations have to be done to take precaution of parent data mutations.
  		}
  	}

  }
</script>
