<template>
	<div id="account">
		<h2>Личный кабинет пользователя {{ this.$store.getters.getUser.post }}</h2>
		<div class="menu">
			<a href="#" class="isActive" @click.prevent=''>Личные данные</a>
			<a href="#" @click.prevent='accountExit'>Выйти из аккаунта</a>
			<hr>
		</div>
		
		<div id="dataAccount">
			<p>ФИО</p>
			<div class="field">
				<label>Имя:</label>
				<input 	type="text"
								v-model.trim="name"
								@input="inputName"
								:class="{ isErr: errorType.name }"
								@keyup.enter="saveData"
								placeholder="Введите имя">
			</div>
			<div class="field">
				<label>Фамилия:</label>
				<input 	type="text"
								v-model.trim="surname"
								@input="inputSurname"
								:class="{ isErr: errorType.surname }"
								@keyup.enter="saveData"
								placeholder="Введите фамилию">
			</div>
			<div class="field">
				<label>Отчество:</label>
				<input 	type="text"
								v-model.trim="patronymic"
								@input="inputPatronymic"
								:class="{ isErr: errorType.patronymic }"
								@keyup.enter="saveData"
								placeholder="Введите отчество"
                autocomplete="off">
			</div>
			<button @click="saveData">Сохранить</button>

			<div>
				<br>
				<span class="error">{{ (textError !== '') ? textError : errorText }}</span>
			</div>

			<p>Смена пароля</p>
			<div class="field wrap">
				<label>Старый пароль:</label>
				<input 	type="password"
								v-model="oldPass"
								@keyup.enter="changePass"
								@input="inputPass"
								placeholder="Введите старый пароль">
			</div>
			<div class="field wrap">
				<label>Новый пароль:</label>
				<input 	type="password"
								v-model="newPass"
								@keyup.enter="changePass"
								@input="inputPass"
								:class="{ isErr: errorType.newPass }"
								placeholder="Введите новый пароль">
			</div>
			<div class="field wrap">
				<label>Подтверждение пароля:</label>
				<input 	type="password"
								v-model="confirmPass"
								@keyup.enter="changePass"
								@input="inputPass"
								:class="{ isErr: errorType.confirmPass }"
								placeholder="Введите подтверждение">
			</div>
			<button @click="changePass">Сменить пароль</button>
		</div>
	</div>
</template>

<script>
	// component account
	// version: 0.0.2
	// date: 04.2020
	// sagit117@gmail.com 

	export default {

		data() {
			return {
				name: this.$store.getters.getUser.name,
				surname: this.$store.getters.getUser.surname,
				patronymic: this.$store.getters.getUser.patronymic,
				newPass: '',
				oldPass: '',
				confirmPass: '',
				errorType: {
					name: false,
					surname: false,
					patronymic: false,
					confirmPass: false,
					newPass: false,
					oldPass: false,
				},
				errorText: '',
			}
		},

		methods: {
			accountExit() {
				// exit
				this.$store.dispatch('setCookie', { name: 'id', value: 0 , delCookie: true });	// удалить коки ИД
				this.$store.commit("setUserData", null);                                    		// разлогинеть пользователя
				this.$router.push('/');                                           							// показать главный экран
			},
			inputName() {
				if (this.name.length > 32) this.name = this.name.slice(0, 32);
				(!this.testInput(this.name) || this.name.length === 0) ? this.errorType.name = true : this.errorType.name = false;
			},
			inputSurname() {
				if (this.surname.length > 32) this.surname = this.surname.slice(0, 32);
				(!this.testInput(this.surname)) ? this.errorType.surname = true : this.errorType.surname = false;
			},
			inputPatronymic() {
				if (this.patronymic.length > 32) this.patronymic = this.patronymic.slice(0, 32);
				(!this.testInput(this.patronymic)) ? this.errorType.patronymic = true : this.errorType.patronymic = false;
			},
			testInput(value) {
				if (value == '') return true;
				let reg = /^[a-zа-яё\s]+$/iu; //[А-Я-Ё]/gi;
				return reg.test(value);
			},
			saveData() {
        if (!this.errorType.name && !this.errorType.surname && !this.errorType.patronymic) {
          this.$store.dispatch("changeDataUser", {  name: this.name, 
                                                    surname: this.surname, 
                                                    patronymic: this.patronymic, 
                                                    id: this.$store.getters.getUser.id });
        }
			},
			inputPass() {
				if (this.newPass.length > 32) this.newPass = this.newPass.slice(0, 32);
				if (this.oldPass.length > 32) this.oldPass = this.oldPass.slice(0, 32);
				if (this.confirmPass.length > 32) this.confirmPass = this.confirmPass.slice(0, 32);
				(this.newPass === '') ? this.errorType.newPass = true : this.errorType.newPass = false;
			},
			changePass() {
				(this.newPass !== this.confirmPass) ? this.errorType.confirmPass = true : this.errorType.confirmPass = false;
				(this.newPass === '') ? this.errorType.newPass = true : this.errorType.newPass = false;

				if (!this.errorType.confirmPass && !this.errorType.newPass) {
          this.$store.dispatch("changePassAccount", { newPass: this.newPass, 
                                                      oldPass: this.oldPass , 
                                                      id: this.$store.getters.getUser.id })
					.then((response) => {
						if (response.errorCode > 0) {
							this.errorType.oldPass = true;
							this.errorText = response.errorText;
						} else {
							this.errorType.oldPass = false;
							this.errorText = '';
						}
          });
        }
			},

		},

		computed: {
			textError: function() {
				if (this.errorType.name) return 'Необходимо заполнить корректное имя!';
				if (this.errorType.surname) return 'Необходимо заполнить корректную фамилию!';
				if (this.errorType.patronymic) return 'Необходимо заполнить корректное отчество!';
				if (this.errorType.confirmPass) return 'Пароль и подтверждение не совпадают!';
				if (this.errorType.newPass) return 'Пароль не должен быть пустым!';
				return '';
			},
		},
	}
</script>

<style scoped>

	.menu {
		width: 100%;
		text-align: right;
		padding-right: 15px;
	}

	.menu > a {
		margin-bottom: 30px;
		margin-left: 10px;
		margin-right: 10px;
	}

	#dataAccount {
		width: 100%;
		text-align: left;
		padding-left: 15px;
		padding-right: 15px;
	}

	.field {
		display: flex;
		align-items: center;
		margin-bottom: 10px;
		max-width: 300px;
	}
	.field>*:first-child {
		width: 110px;
	}

	.wrap {
		flex-wrap: wrap;
	}
	.wrap>*:first-child {
		width: auto;
	}

	.isActive {
		border-bottom: 14px solid #42b983;
	}

	.isErr {
		border-color: red;
	}

	.error {
		color: red;
		text-align: center;
	}

	p {
		font-weight: bold;
	}

	input {
		width: 100%;
		max-width: 300px;
	}

	h2 {
		text-align: left;
		padding-left: 15px;
	}

	hr {
		margin: 15px;
	}

@media (min-width: 100px) and (max-width: 415px) {
	#dataAccount {
		width: auto;
	}
	.menu > a {
		font-size: 15px;
	}
	.field {
		flex-wrap: wrap;
	}

}

</style>