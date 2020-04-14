<?php
	/* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
	header("Access-Control-Allow-Headers: *");

	require 'image.php';
	require 'fotoRecipes.php';
  require 'login.php';

	class ResponseSR {
		public $id = 0;
		public $count_error_img = 0;
		public $errorText = '';
	}

  if (isset($_POST['name'])) {
    // сохранить фото рецепт в базу
    $response = new ResponseSR();

    if (!verifyUser($_COOKIE['id'])) { // проверка пользователя
      $response->errorText = "Отказано в доступе!";
      exit(json_encode($response)); 
    }

    $name = $_POST['name'];
    $parent_id = intval($_POST['parent_id']);
    $author_id = intval($_POST['author_id']);
    $diet = intval($_POST['diet']);

    $rule = getUser("id", intval($_COOKIE['id']))[0]->rule;
    if ($rule != "extra_user" and $author_id != intval($_COOKIE['id'])) {
      $res->errorText = "Отказано в доступе! Роль доступа: $rule, id_post: $id, id_cookie=".intval($_COOKIE['id']);
      exit(son_encode($res));
    }

    $arrImg = (!isset($_POST['fileFoto'])) ? saveImageFiles($_FILES) : saveImage($_POST['fileFoto']);
    $id = saveFotoRecipes($arrImg, $name, $parent_id, $author_id, $diet);

    $i = 0;
    $count = 0;

    foreach ($arrImg as $key => $value) {
    	$image_name = $value->image_name;
    	if ($value->errorText != 0) $i++;
    	$count++;
    }

    if ($id > 0) {
    	$response->id = $id;
    	$response->count_error_img = $i;
    	$errorText = ($i > 0) ? "Загружено $i из $count фото" : '';
    } else {
    	$response->id = $id;
    	$response->count_error_img = $i;
    	$errorText = 'Произошла ошибка при попытке создать запись!';
    }

    echo json_encode($response); exit();
  }

?>