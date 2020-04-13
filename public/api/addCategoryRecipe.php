<?php
	/* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
	header("Access-Control-Allow-Headers: *");

  require 'categoriesRecipe.php';
  require_once 'user.php';
  require 'login.php';

  class Response {
    public $id = 0;
    public $errorCode = 0;
    public $errorText = '';
  }

  if (isset($_GET['name'])) {
    $res = new Response();

    if (!verifyUser($_COOKIE['id'])) { // проверка пользователя
      $res->errorCode = 1;
      $res->errorText = "Отказано в доступе!";
      exit(json_encode($res)); 
    }

    $name = $_GET['name'];
    $author_id = intval($_GET['author_id']);
    $parent_id = $_GET['parent_id'];

    if ($author_id === 0) {
      $res->errorCode = 2;
      $res->errorText = "Автор не определен!";
      exit(json_encode($res));
    }

    if ($name === '') {
      $res->errorCode = 4;
      $res->errorText = "Имя категории не должно быть пустым!";
      exit(json_encode($res));
    }

    $rule = getUser("id", intval($_COOKIE['id']))[0]->rule;
    if ($rule !== "extra_user" and $author_id !== intval($_COOKIE['id'])) {
      $res->errorCode = 1;
      $res->errorText = "Отказано в доступе! Роль доступа: $rule";
      exit(json_encode($res));
    }
    	
    $id = addNewCategory($parent_id, $name, $author_id);
    if ($id > 0) {
      $res->id = $id;
      $res->errorCode = 0;
      $res->errorText = '';
    	echo json_encode($res);
    } else {
      $res->id = $id;
      $res->errorCode = 3;
      $res->errorText = 'Не удалось создать категорию рецепта!';
    	echo json_encode($res);
    }
  }
?>