<template>
	<div :class="[{ error: type === 1, alert: type === 2, success: type === 3}, 'alert' ]" id="alert">
		<h3>{{ this.$store.getters.getShowAlert.caption }}</h3>
		<hr>
		<p>{{ this.$store.getters.getShowAlert.text }}</p>
		<div class="close" title="Закрыть" @click="close()">
			&#215;
		</div>
	</div>
</template>

<script>
	// component alert
	// version: 0.0.2
	// date: 04.2020
	// sagit117@gmail.com

	export default {

		methods: {
			close() {
				this.$store.commit('setShowAlert', false);
			}
		},

		computed: {
			type: function() {
				return this.$store.getters.getShowAlert.type;
			}
		}
	}
</script>

<style scoped>
	.alert {
		position: fixed;
		top: 40%;
		max-width: 80%;
		left: 10%;
		right: 10%;
		border-radius: 5px;
		padding: 10px;
		box-shadow: 3px 3px 6px #000;
		z-index: 99999;
		background-color: white;
	}

	.error {
		background-color: #bf5e79;
	}
	
	.success {
		background-color: #84b85c;
	}

@media (min-width: 100px) and (max-width: 415px) {
	.alert {
		width: auto;
		left: 20px;
		right: 20px;
	}
}
</style>