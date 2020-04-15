<template>
	<div id="registration">
		<div class="field">
			<label>Логин: </label>
			<input 	type="text" 
							autofocus
							v-model.trim="login"
							@input="inputLogin"
							:class="{ isErr: errorType.name }"
							@keyup.enter="send"
							placeholder="Введите логин">
		</div>
		<div class="field">
			<label>Пароль: </label>
			<input 	type="password"
							v-model.trim="pass"
							@input="inputPass"
							:class="{ isErr: errorType.pass }"
							@keyup.enter="send"
							placeholder="Введите пароль">
		</div>
		<div class="field">
			<label>Подтверждение: </label>
			<input 	type="password"
							v-model.trim="confirmPass"
							@input="inputPass"
							@keyup.enter="send"
							:class="{ isErr: this.errorType.confirmPass }"
							placeholder="Введите подтверждение">
		</div>
		<div class="field">
			<label>Проверочный код: </label>
			<button @click="sendCode" v-if="timer === 30">Выслать код</button>
			<span v-else>подождите {{ timer }} сек.</span>
		</div>
		<div class="field">
				<input 	type="text"
								v-model.trim="userCode"
								@keyup.enter="send"
								:class="{ isErr: this.errorType.code }"
								placeholder="Введите код">
			</div>
		<p><span class="error">{{ (errorText !== '') ? errorText : textError }}</span></p>
		<div id="btn">
			<button @click="send">Зарегистрироваться</button>
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
				pass: '',
				confirmPass: '',
				code: '',
				userCode: '',
				errorText: '',
				errorType: {
					name: false,
					pass: false,
					email: false,
					confirmPass: false,
					code: false,
					post: false
				},
				timer: 30,
				post: '',
			}
		},

		methods: {
			inputLogin() {
				if (this.login.length > 64) this.login = this.login.slice(0, 64);
				this.minLengthLogin();
			},
			inputPass() {
				if (this.pass.length > 64) this.pass = this.pass.slice(0, 32);
				if (this.confirmPass.length > 64) this.confirmPass = this.confirmPass.slice(0, 32);
				this.minLengthPass();
			},
			minLengthLogin() {
				(this.login.length === 0) ? this.errorType.name = true : this.errorType.name = false;
			},
			minLengthPass() {
				(this.pass.length === 0) ? this.errorType.pass = true : this.errorType.pass = false;
			},
			sendCode() {
				// Выслать код
				this.minLengthLogin;
				this.errorText = '';

				if (!this.testEmail(this.login)) {
					this.errorType.email = true;
				} else {
					this.$store.dispatch('sendCodeReg', this.login)
					.then((response) => {
						if (response.errorCode === 0) {
							this.code = response.code;
							this.post = response.post;
							this.errorType.email = false;
							this.setTimeOut();
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
			setTimeOut() {
				// таймаут кнопки 
				let timerId = setInterval(()=> {
					this.timer--;
					if (this.timer === 0) {
						clearInterval(timerId);
						this.timer = 30;
						this.sendVisible = true;
					}
				}, 1000);
			},
			send() {
				// Отправка данных
				this.minLengthLogin();
				this.minLengthPass();
				this.errorText = '';

				// проверка email
				if (!this.testEmail(this.login)) {
					this.errorType.email = true; 
				} else {
					this.errorType.email = false;
				}
				if (this.post !== this.login) {
					this.errorType.post = true;
				} else {
					this.errorType.post = false;
				}
				// test pass
				if (this.pass !== this.confirmPass) {
					this.errorType.confirmPass = true; 
				} else {
					this.errorType.confirmPass = false; 
				}
				// проверка кода
				if (this.code !== this.userCode) {
					this.errorType.code = true;
				} else {
					this.errorType.code = false;
				}

				for (let err in this.errorType) {
					if (this.errorType[err]) return;
				}

				this.$store.dispatch("regUser", { login: this.login, pass: this.pass })
				.then((response) => {
					if (response.id > 0) {
						this.$emit('open-page', 1);
					} else {
						this.errorText = response.errorText;
					}
				});
			},
		},

		computed: {
			textError: function() {
				if (this.errorType.name) return 'Необходимо заполнить логин!';
				if (this.errorType.pass) return 'Необходимо заполнить пароль!';
				if (this.errorType.email) return 'Необходимо ввести корректный e-mail!';
				if (this.errorType.confirmPass) return 'Пароль и проверка не совпадает!';
				if (this.errorType.code) return 'Код и проверка не совпадает!';
				if (this.errorType.post) return 'Указанный email не совпадает с email, на который был выслан код';
				return '';
			},
		}

	}
</script>

<style scoped>
	#registration {
		position: fixed;
		width: 300px;
		top: calc(50% - 200px);
		left: calc(50% - 150px);
		box-shadow: 3px 3px 6px #000;
		background-color: white;
		border-radius: 5px;
		text-align: left;
		z-index: 100;
		padding: 25px;
	}

	.field {
		display: flex;
		align-items: center;
		margin-bottom: 10px;
		flex-wrap: wrap;
	}

	input {
		width: 100%;
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

@media (min-width: 100px) and (max-width: 415px) {
	#registration {
		width: auto;
		left: 20px;
		right: 20px;
	}
}

</style>