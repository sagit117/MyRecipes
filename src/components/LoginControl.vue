<template>
	<div>
		<a href="#" @click.prevent="openPage(1)" v-if="this.$store.getters.getUser.id === 0"> Войти </a>
		<router-link to="/account" v-else>Личный кабинет</router-link>

		<FormLogin 	v-if="showPage === 1"
								@open-page="openPage" />
		<FormResetPass 	v-if="showPage === 2" 
										@open-page="openPage" />
		<FormRegistration v-if="showPage === 3" 
											@open-page="openPage" />

	</div>
</template>

<script>
	// component login 
	// version: 0.0.2
	// date: 04.2020
	// sagit117@gmail.com
	import FormLogin from './FormLogin.vue'
	import FormResetPass from './FormResetPass.vue'
	import FormRegistration from './FormRegistration.vue'

	export default {
		components: {
			FormLogin,
			FormResetPass,
			FormRegistration
		},

		data() {
			return {
				showPage: 0,	// 1 - login, 2 - reset pass, 3 - reg
			}
		},

		methods: {
			openPage(state) {
				this.showPage = state;
			},
		},
	}	
</script>

<style scoped>
	a {
		padding: 15px;
		line-height: 2em;
	}
</style>