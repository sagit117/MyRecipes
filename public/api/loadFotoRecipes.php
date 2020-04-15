<?php
	/* MyRecopes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
	header("Access-Control-Allow-Headers: *");

	require 'fotoRecipes.php';
	require 'login.php';
	require_once 'user.php';

	class Response {
		public $errorCode = 0;
		public $errorText = '';
	}

	if (count($_POST) > 0) {
		// загрузка фото рецептов
		$res = new Response();
		$author_id = intval($_POST['author_id']);

		if (!verifyUser($_COOKIE['id'])) { // проверка пользователя
      $res->errorCode = 1;
      $res->errorText = "Отказано в доступе!";
      exit(json_encode($res)); 
    }

    $rule = getUser("id", intval($_COOKIE['id']))[0]->rule;
    if ($rule !== "extra_user" and $author_id !== intval($_COOKIE['id'])) {
      $res->errorCode = 1;
      $res->errorText = "Отказано в доступе! Роль доступа: $rule";
      exit(json_encode($res));
    }

		$parent_id = $_POST['parent_id'];
		$page = intval($_POST['page']);
		$diet = $_POST['diet'];
		$limit = $_POST['limit'];

		$str = getFotoRecipes($parent_id, $author_id, $page, $diet, $limit);

		echo json_encode($str);
	}
?>