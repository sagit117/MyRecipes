<template>
	<div id="login"> 
		<div class="field">
			<label>Логин: </label>
			<input 	type="text" 
							autocomplete="on" 
							autofocus
							v-model.trim="login"
							@input="inputLogin"
							:class="{ isErr: errorText !== '' }"
							@keyup.enter="sendData"
							placeholder="Введите логин">
		</div>
		<div class="field">
			<label>Пароль: </label>
			<input 	type="password"
							v-model.trim="pass"
							@input="inputPass"
							@keyup.enter="sendData"
							placeholder="Введите пароль">
		</div>

		<div id="href">
			<a 	href="#" 
					@click.prevent="$emit('open-page', 2)">Забыли пароль?</a> | 
			<a 	href="#" 
					@click.prevent="$emit('open-page', 3)">Регистрация</a>
		</div>
		<p class="error"><span>{{ errorText }}</span></p>
		<div id="btn">
			<button @click="sendData">Войти</button>
		</div>
		<div class="close" title="Закрыть" @click="$emit('open-page', 0)">
			&#215;
		</div>
	</div>
</template>

<script>
	// component login 
	// version: 0.0.2
	// date: 04.2020
	// sagit117@gmail.com
	
	export default {

		data() {
			return {
				login: '',
				pass: '',
				errorText: '',
			}
		},

		methods: {
			inputPass() {
				if (this.login.length > 32) this.login = this.login.slice(0, 32);
			},
			inputLogin() {
				if (this.login.length > 64) this.login = this.login.slice(0, 64);
				this.minLength();
			},
			minLength() {
				if (this.login.length === 0) {
					this.errorText = 'Необходимо ввести логин!';
				} else {
					this.errorText = '';
				}
			},
			testEmail(email) {
				let reg = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/;
				return reg.test(email);
			},
			sendData() {
				// Логинемся
				this.minLength();

				if (this.errorText === '') {
					if (!this.testEmail(this.login)) {
						this.errorText = 'Необходимо ввести корректный e-mail!';
						return;
					}
					
					this.$store.dispatch('sendLogin', { login: this.login, pass: this.pass })
					.then((response) => {
						if (response === 'ok') {
							this.$emit("open-page", 0);
						} else {
							this.errorText = response.errorText;
						}
					});
				}
			},

		}

	}
</script>

<style scoped>
	#login {
		position: fixed;
		width: 300px;
		top: calc(50% - 135px);
		left: calc(50% - 150px);
		box-shadow: 3px 3px 6px #000;
		background-color: white;
		border-radius: 5px;
		text-align: left;
		z-index: 100;
		padding: 20px;
	}

	.field {
		display: flex;
		align-items: center;
		margin-bottom: 10px;
	}
	.field>*:first-child {
		width: 90px;
	}

	#href {
		display: flex;
		align-items: center;
		justify-content: center;
		/*margin: 20px;*/
    margin-top: 20px;
    margin-bottom: 20px;
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

	a {
		margin-left: 5px;
		margin-right: 5px;
	}

@media (min-width: 100px) and (max-width: 415px) {
	#login {
		width: auto;
		left: 20px;
		right: 20px;
	}

	.field {
		flex-wrap: wrap;
	}

	#href {
		margin-left: 0px;
		margin-right: 0px;
	}

	a {
		font-size: 14px;
	}
}
</style>