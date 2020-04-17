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

  if (isset($_POST['id'])) {
    // сохранить фото рецепт в базу
    $response = new ResponseSR();

    if (!verifyUser($_COOKIE['id'])) { // проверка пользователя
      $response->errorText = "Отказано в доступе!";
      exit(json_encode($response)); 
    }

    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $parent_id = intval($_POST['parent_id']);
    $diet = intval($_POST['diet']);

    $rule = getUser("id", intval($_COOKIE['id']))[0]->rule;
    if ($rule !== "extra_user" and getAuthorFotoRecipes($id) !== intval($_COOKIE['id'])) {
      $res->errorText = "Отказано в доступе! Роль доступа: $rule";
      exit(json_encode($res));
    }

    updateFotoRecipe($id, $name, $parent_id, $diet);

    $arrImg = json_decode($_POST['img']); // массив объектов для фото
    $arrImgID = getArrayFotoID($id);

    $count = 0;
    foreach ($arrImg as $img) {
      if ($img->new) {
        $fileName = saveImage(array($img->img))[0]->image_name;
        
      }
      $count++;
    }

  }

?>