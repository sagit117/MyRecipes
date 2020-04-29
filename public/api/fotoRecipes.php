<?php
	/* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  // getAuthorFotoRecipes   - получить ИД автора фото рецепта
  // saveFotoRecipes        - сохранить фото рецепт
  // getFotoRecipes         - получить список фото рецептов по ИД групп, ИД автора, страница * лимит, лимит, диет
  // getAuthorFoto          - получить ИД автора фото по ИД фото
  // deleteFoto             - удалить фото по ИД фото
  // updateFotoRecipe       - обновить фото рецепт
  // getArrayFoto           - получить массив фото
  // updateImgRecords       - перезаписать запись с фото от оторецепта
  // createImgRecords       - создать запись с фото для фото рецепта
  // addFavoriteFotoRecipe  - добавить рецепт в избранное
  // rmFavoriteFotoRecipe   - удалить рецепт из избранного
  // getTotalFotoRecipes    - получить количество страниц
  // rmFotoRecipe           - удалить рецепт

  require 'connect.php';
  require_once 'image.php';

  class DataRecipes {
    public $id = 0;
    public $parent_id = 0;
    public $name = '';
    public $author_id = 0;
    public $diet = 0;
    public $img = array();
    public $fav = false;
  }

  class DataImg {
    public $id = 0;
    public $parent_id = 0;
    public $path_img = '';
    public $author_id = 0;
  }

  function saveFotoRecipes($arrImg, $name, $parent_id, $author_id, $diet) {
    // сохранить фото рецепт
    global $link;
    $parent_id = intval($parent_id);
    $name = mysqli_real_escape_string($link, $name);
    $author_id = intval($author_id);
    $diet = intval($diet);

    mysqli_query($link, "INSERT INTO 	`foto_recipes` (`parent_id`, `name`, `author_id`, `diet`) 
    								VALUES 	('$parent_id', '$name', '$author_id', '$diet')") or die(0);
    $id = mysqli_insert_id($link);

    if ($id > 0) {
    	foreach ($arrImg as $key => $value) {
    		$image_name = $value->image_name;
    		if ($value->errorText == 0) createImgRecords($id, $image_name, $author_id);
    	}
    }

    return $id;
  }

  function createImgRecords($parent_id, $path_img, $author_id) {
    // создать запись с фото для фото рецепта
    global $link;
    $parent_id = intval($parent_id);
    $path_img = mysqli_real_escape_string($link, $path_img);
    $author_id = intval($author_id);

    mysqli_query($link, "INSERT INTO 	`img_foto_recipes` (`parent_id`, `path_img`, `author_id`) 
    											VALUES 	('$parent_id', '$path_img', '$author_id')");
  }

  function getFotoRecipes($parent_id, $author_id, $page, $diet, $limit, $fav) {
    // получить список фото рецептов по ИД групп, ИД автора, страница * лимит, лимит, диет
    global $link;
    $parent_id = intval($parent_id);
    $author_id = intval($author_id);
    $limit = intval($limit);
    $page = intval($page) * $limit;
    $diet = intval($diet);

    $str_parent = ($parent_id === 0) ? "" : " `parent_id`='$parent_id' ";
    $str_diet = ($diet === 0) ? "" : " `diet`='$diet' ";
    $str_author = ($author_id === 0) ? "" : " `author_id`='$author_id' ";

    if ($str_parent !== '' AND ($str_diet !== '' OR $str_author !== '')) $str_parent .= " AND ";
    if ($author_id !== '' AND $str_diet !== '') $str_author .= " AND ";
    $str_where = ($parent_id === 0 AND $author_id === 0 AND $diet === 0) ? "" : " WHERE ";
    $str_limit = ($limit === 0) ? "" : " LIMIT $page, $limit ";

    $arrayRecipes = array();

    $result = mysqli_query($link, "SELECT * FROM `foto_recipes` $str_where $str_parent $str_author $str_diet $str_limit");
    while ($recipes = mysqli_fetch_assoc($result)) {
      $data = new DataRecipes();
      $arrayImg = array();

      $data->id = $recipes['id'];
      $data->parent_id = $recipes['parent_id'];
      $data->name = $recipes['name'];
      $data->author_id = $recipes['author_id'];
      $data->diet = $recipes['diet'];

      // узнаем добавлен рецепт в избранное или нет
      $row = mysqli_query($link, "SELECT count(*) FROM `favorite_foto_recipes` 
        WHERE `id_user`='$author_id' AND `id_recipe`='". $data->id ."' LIMIT 1");
      if (mysqli_fetch_row($row)[0] > 0) $data->fav = true;

      // получаем массив фото
      $resultImg = mysqli_query($link, "SELECT * FROM `img_foto_recipes` WHERE `parent_id`='". $data->id ."'");
      while ($img = mysqli_fetch_assoc($resultImg)) {
        $dataImg = new DataImg();
        $dataImg->id = $img['id'];
        $dataImg->parent_id = $img['parent_id'];
        $dataImg->author_id = $img['author_id'];
        $dataImg->path_img = $img['path_img'];
        
        array_push($arrayImg, $dataImg);
      }

      $data->img = $arrayImg;

      if (intval($fav) === 1) {
        if ($data->fav === true) array_push($arrayRecipes, $data);
      } else {
        array_push($arrayRecipes, $data);
      }
    }

    return $arrayRecipes;
  }

  function getAuthorFoto($id) {
    // Получить ИД автора фото по ИД записи
    global $link;
    $id = intval($id);
    $result = mysqli_query($link, "SELECT `author_id` FROM `img_foto_recipes` WHERE `id`='$id' LIMIT 1");
    return intval(mysqli_fetch_row($result)[0]);
  }

  function getAuthorFotoRecipes($id) {
    // Получить ИД автора фото рецепта
    global $link;
    $id = intval($id);
    $result = mysqli_query($link, "SELECT `author_id` FROM `foto_recipes` WHERE `id`='$id' LIMIT 1");
    return intval(mysqli_fetch_row($result)[0]);
  }

  function deleteFoto($id) {
    // удалить фото из фото рецепта
    global $link;
    $id = intval($id);

    $result = mysqli_query($link, "SELECT `path_img` FROM `img_foto_recipes` WHERE `id`='$id' LIMIT 1");
    $path_img = mysqli_fetch_row($result)[0];

    if ($path_img != '') {
      mysqli_query($link, "DELETE FROM `img_foto_recipes` WHERE `id`='$id'");
      return $path_img;
    } else {
      return 0;
    }
  }

  function updateFotoRecipe($id, $name, $parent_id, $diet) {
    // Обновить фото рецепт
    global $link;
    $id = intval($id);
    $name = mysqli_real_escape_string($link, $name);
    $parent_id = intval($parent_id);
    $diet = intval($diet);

    mysqli_query($link, "UPDATE `foto_recipes` SET  `name`='$name', 
                                                    `parent_id`='$parent_id', 
                                                    `diet`='$diet' WHERE `id`='$id'") or die(mysqli_error($link));
    return true;
  }

  function getArrayFoto($id) {
    // получить массив фото
    global $link;
    $id = intval($id);
    $arr = array();

    $result = mysqli_query($link, "SELECT `id`, `path_img` FROM `img_foto_recipes` WHERE `parent_id`='$id'");
    while ($img = mysqli_fetch_assoc($result)) {
      $foto = new DataImg();
      $foto->id = $img['id'];
      $foto->path_img = $img['path_img'];
      array_push($arr, $foto);
    }

    return $arr;
  }

  function updateImgRecords($id, $fileName) {
    // перезаписать запись с фото от оторецепта
    global $link;
    $id = intval($id);
    $fileName = mysqli_real_escape_string($link, $fileName);

    mysqli_query($link, "UPDATE `img_foto_recipes` SET `path_img`='$fileName' WHERE `id`='$id'");
  }

  function addFavoriteFotoRecipe($id_user, $id_recipe) {
    // добавить рецепт в избранное
    global $link;
    $id_recipe = intval($id_recipe);
    $id_user = intval($id_user);

    mysqli_query($link, "INSERT INTO `favorite_foto_recipes` (`id_recipe`, `id_user`) 
      VALUES ('$id_recipe', '$id_user')");
    return mysqli_insert_id($link);
  }

  function rmFavoriteFotoRecipe($id_user, $id_recipe) {
    // удалить рецепт из избранного
    global $link;
    $id_recipe = intval($id_recipe);
    $id_user = intval($id_user);

    if ($id_user > 0) $str_user = "`id_user`='$id_user' AND ";

    mysqli_query($link, "DELETE FROM `favorite_foto_recipes` WHERE $str_user `id_recipe`='$id_recipe'");
  }

  function getTotalFotoRecipes($parent_id, $author_id, $diet) {
    // получить количество страниц
    global $link;
    $parent_id = intval($parent_id);
    $author_id = intval($author_id);
    $diet = intval($diet);

    $str_parent = ($parent_id === 0) ? "" : " `parent_id`='$parent_id' ";
    $str_diet = ($diet === 0) ? "" : " `diet`='$diet' ";
    $str_author = ($author_id === 0) ? "" : " `author_id`='$author_id' ";

    if ($str_parent !== '' AND ($str_diet !== '' OR $str_author !== '')) $str_parent .= " AND ";
    if ($author_id !== '' AND $str_diet !== '') $str_author .= " AND ";
    $str_where = ($parent_id === 0 AND $author_id === 0 AND $diet === 0) ? "" : " WHERE ";

    $result = mysqli_query($link, "SELECT count(*) FROM `foto_recipes` $str_where $str_parent $str_author $str_diet");
    return mysqli_fetch_row($result)[0];
  }

  function rmFotoRecipe($id_recipe) {
    // удалить рецепт
    global $link;
    $id_recipe = intval($id_recipe);

    mysqli_query($link, "DELETE FROM `foto_recipes` WHERE `id`='$id_recipe'"); // удалить запись рецепта
    rmFavoriteFotoRecipe(0, $id_recipe); // удалить рецепт из избранного
    $arr = getArrayFoto($id_recipe);

    foreach ($arr as $img) { // удалить фото
      deleteImg(deleteFoto($img->id));
    }
  }

?>