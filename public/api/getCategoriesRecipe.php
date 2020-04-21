<?php
	/* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  require 'categoriesRecipe.php';
  require_once 'user.php';
  require 'login.php';

  header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
  header("Access-Control-Allow-Headers: *");

	class Response {
		public $errorCode = 0;
    public $errorText = '';
    public $data = '';
	}

	$res = new Response;
	$id = intval($_GET['author_id']);

	if (!verifyUser($_COOKIE['id'])) { // проверка пользователя
		$res->errorCode = 77;
		$res->errorText = "Отказано в доступе!";
		exit(json_encode($res)); 
	}

	$rule = getUser("id", intval($_COOKIE['id']))[0]->rule;
	if ($rule != "extra_user" and $id != intval($_COOKIE['id'])) {
		$res->errorCode = 1;
		$res->errorText = "Отказано в доступе! Роль доступа: $rule";
		exit(json_encode($res));
	} 

	if (isset($_GET['parent_id'])) {
		$parent_id = $_GET['parent_id'];
    $res->data = getCategories($parent_id, $id);
    exit(json_encode($res));
	}
?>