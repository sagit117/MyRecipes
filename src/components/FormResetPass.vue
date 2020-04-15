<template>
	<div id="resetPass">
		<div id="field">
			<label>Логин(e-mail): </label>
			<input 	type="text" 
							autofocus
							v-model.trim="login"
							@input="inputLogin"
							:class="{ isErr: errorText !== '' }"
							@keyup.enter="sendData"
							placeholder="Введите адрес e-mail">
		</div>
		<p class="error"><span>{{ errorText }}</span></p>
		<div id="btn">
			<button @click="sendData">Отправить</button>
		</div>
		<div class="close" @click="$emit('open-page', 1)" title="Закрыть">
			&#215;
		</div>
	</div>
</template>

<script>
	
	export default {
		data() {
			return {
				login: '',
				errorText: '',
			}
		},

		methods: {
			inputLogin() {
				if (this.login.length > 64) this.login = this.login.slice(0, 64);
				this.minLength();
			},
			minLength() {
				if (this.login.length === 0) {
					this.errorText = 'Необходимо ввести логин';
				} else {
					this.errorText = '';
				}
			},
			sendData() {
				if (!this.testEmail(this.login)) {
					this.errorText = "Необходимо ввести корректный e-mail"
				} else {
					this.errorText = '';
				}

				if (this.errorText === '') {
					this.$store.dispatch("changePass", this.login)
					.then((response) => {
						if (response.errorCode === 0) {
							this.$emit('open-page', 1);
						} else {
							this.errorText = response.errorText;
						}
					});
				}
			},
			testEmail(email) {
				let reg = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/;
				return reg.test(email);
			},

		}

	}
</script>

<style scoped>
	#resetPass {
		position: fixed;
		width: 300px;
		top: calc(50% - 135px);
		left: calc(50% - 150px);
		box-shadow: 3px 3px 6px #000;
		background-color: white;
		border-radius: 5px;
		text-align: left;
		z-index: 100;
		padding: 25px;
	}

	#field {
		display: flex;
		align-items: center;
		margin-bottom: 10px;
	}
	#field>*:first-child {
		width: 160px;
	}

	#btn {
		float: right;
	}

	.isErr {
		border-color: red;
	}

	.error {
		color: red;
		text-align: center;
	}

	input {
		width: 100%;
	}

@media (min-width: 100px) and (max-width: 415px) {
	#resetPass {
		width: auto;
		left: 20px;
		right: 20px;
	}

	#field {
		flex-wrap: wrap;
	}
}

</style>