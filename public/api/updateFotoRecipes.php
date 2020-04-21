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
  
  class ResponseFR {
    public $errorText = '';
    public $errorCode = 0;
	}

  if (isset($_POST['id'])) {
    // сохранить фото рецепт в базу
    $res = new ResponseFR();

    if (!verifyUser($_COOKIE['id'])) { // проверка пользователя
      $res->errorText = "Отказано в доступе!";
      $res->errorCode = 77;
      exit(json_encode($response)); 
    }

    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $parent_id = intval($_POST['parent_id']);
    $diet = intval($_POST['diet']);
    $author_id = getAuthorFotoRecipes($id);

    $rule = getUser("id", intval($_COOKIE['id']))[0]->rule;
    if ($rule !== "extra_user" and $author_id !== intval($_COOKIE['id'])) {
      $res->errorText = "Отказано в доступе! Роль доступа: $rule";
      $res->errorCode = 1;
      exit(json_encode($res));
    }

    updateFotoRecipe($id, $name, $parent_id, $diet);

    $arrImg = json_decode($_POST['img']); // массив объектов для фото
    $arrDataImg = getArrayFoto($id);

    $count = 0;
    foreach ($arrImg as $img) {
      if ($img->new) {                                                    // если пометка, что файл новый
        $fileName = saveImage(array($img->img))[0]->image_name;           // создать файл на сервере

        if (intval($img->id) > 0) {                                       // если ИД > 0 значить файл записан поверх имеющегося
          foreach ($arrDataImg as $dataImg) {                             // поиск по массиву со старыми значениями фото
            if ($dataImg->id == $img->id) deleteImg($dataImg->path_img);  // если найдено старое фото, тогда его удалить
          }  
        }
        
        // записать новые данные в БД по верх старых
        if ($count < count($arrDataImg)) {
          updateImgRecords($arrDataImg[$count]->id, $fileName);
        } else {
          createImgRecords($id, $fileName, $author_id);
        }
      } else {
        // записать новые данные в БД по верх старых
        if ($count < count($arrDataImg)) {
          updateImgRecords($arrDataImg[$count]->id, $img->img);
        } else {
          createImgRecords($id, $img->img, $author_id);
        }
      }

      $count++;
    }
    echo json_encode($res);
  }

?>