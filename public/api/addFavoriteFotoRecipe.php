<?php
  /* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
	header("Access-Control-Allow-Headers: *");

  require_once 'user.php';
  require 'login.php';
  require 'fotoRecipes.php';

  class Response {
		public $errorCode = 0;
		public $errorText = '';
	}

	$res = new Response;

	if (!verifyUser($_COOKIE['id'])) { // проверка пользователя
		$res->errorCode = 77;
		$res->errorText = "Отказано в доступе!";
		exit(json_encode($res)); 
  }
  
  $id_recipe = intval($_GET['id_recipe']);

  if ($id_recipe > 0) {
    $id_user = intval($_GET['id_user']);

    $rule = getUser("id", intval($_COOKIE['id']))[0]->rule;
		if ($rule !== "extra_user" and $id_user !== intval($_COOKIE['id'])) { // проверка возможности изменить запись
			$res->errorCode = 1;
			$res->errorText = "Отказано в доступе! Роль доступа: $rule";
			exit(json_encode($res));
    }

    $id = addFavoriteFotoRecipe($id_user, $id_recipe);
    if ($id > 0) {
      exit(json_encode($res));
    } else {
      $res->errorCode = 2;
		  $res->errorText = "Не удалось создать запись!";
		  exit(json_encode($res));
    }
  }

?>