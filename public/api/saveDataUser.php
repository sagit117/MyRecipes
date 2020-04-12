<?php
	/* MyRecipes */
    /* version: 0.0.2 */
    /* author: Aksenov Pavel */
    /* 04.2020 */
    /* sagit117@gmail.com */

    header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
	header("Access-Control-Allow-Headers: *");

	//require 'connect.php';
    require_once 'user.php';
    require 'login.php';

	class Response {
		public $errorCode = 0;
		public $errorText = '';
	}

	$res = new Response;

	if (!verifyUser($_COOKIE['id'])) { // проверка пользователя
		$res->errorCode = 1;
		$res->errorText = "Отказано в доступе!";
		exit(json_encode($res)); 
	}

	if (intval($_POST['id']) > 0) {
		$id = intval($_POST['id']);
		$rule = getUser("id", intval($_COOKIE['id']))[0]->rule;

		if ($rule != "extra_user" and $id != intval($_COOKIE['id'])) {
			$res->errorCode = 1;
			$res->errorText = "Отказано в доступе! Роль доступа: $rule, id_post: $id, id_cookie=".intval($_COOKIE['id']);
			echo json_encode($res);
		} else {
			if (isset($_POST['name'])) $name = updateUser("name", $_POST['name'], "id", $id);
			if (isset($_POST['surname'])) $surname = updateUser("surname", $_POST['surname'], "id", $id);
			if (isset($_POST['patronymic'])) $patronymic = updateUser("patronymic", $_POST['patronymic'], "id", $id);

			echo json_encode($res);
		}
	} else {
	    $res->errorCode = 2;
		$res->errorText = "Нет запроса!";
		echo json_encode($res);
	}
?>