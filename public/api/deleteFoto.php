<?php
	/* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
	header("Access-Control-Allow-Headers: *");

	require 'fotoRecipes.php';
	require 'image.php';
	require 'login.php';
	require_once 'user.php';

	class Response {
		public $errorCode = 0;
		public $errorText = '';
	}

	if (intval($_GET['id']) > 0) {
		$res = new Response();

		if (!verifyUser($_COOKIE['id'])) { // проверка пользователя
			$res->errorCode = 77;
			$res->errorText = "Отказано в доступе!";
			exit(json_encode($res)); 
		}

		$id = $_GET['id'];
    $author_id = getAuthorFoto($id);
    
		$rule = getUser("id", intval($_COOKIE['id']))[0]->rule;
		if ($rule != "extra_user" and $author_id != intval($_COOKIE['id'])) {
			$res->errorCode = 4;
			$res->errorText = "Отказано в доступе! Роль доступа: $rule";
			exit(json_encode($res));
		}

		$path_img = deleteFoto($id);

		if ($path_img !== 0) {
			if (deleteImg($path_img)) {
				$res->errorCode = 0;
				$res->errorText = "";
			} else {
				$res->errorCode = 2;
				$res->errorText = "Не удалось удалить фото!";
			}
		} else {
			$res->errorCode = 1;
			$res->errorText = "ID фото не существует! ";
		}

		echo json_encode($res);
	}

?>