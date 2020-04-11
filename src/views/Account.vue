<template>
	<div id="account">
		<h2>Личный кабинет пользователя {{ this.$store.getters.getUser.post }}</h2>
		<div class="menu">
			<a href="#">Личные данные</a>
			<a href="#">Выйти из аккаунта</a>
			<hr>
		</div>
		
		<div class="dataAccount">
			<p>ФИО</p>
			<label>Имя:</label>
			<input 	type="text">
			<label>Фамилия:</label>
			<input 	type="text">
			<label>Отчество:</label>
			<input 	type="text">
			<button>Сохранить</button>

			<div>
				<p>
					<span class="err">
						
					</span>
					<br>
				</p>
			</div>

			<p>Смена пароля</p>
			<label>Старый пароль:</label>
			<input 	type="password">
			<label>Новый пароль:</label>
			<input 	type="password">
			<label>Подтверждение пароля:</label>
			<input 	type="password">
			<button>Сменить пароль</button>
			<br><br>
		</div>
	</div>
</template>

<script>
	// component account
	// version: 0.0.2
	// date: 04.2020
	// sagit117@gmail.com 

	export default {

		

	}
</script>

<style scoped>
	h2 {
		text-align: left;
		padding-left: 15px;
	}

</style>