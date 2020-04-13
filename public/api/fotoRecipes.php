<?php
	/* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  require 'connect.php';

  class DataRecipes {
    public $id = 0;
    public $parent_id = 0;
    public $name = '';
    public $author_id = 0;
    public $diet = 0;
    public $img = array();
  }

  class DataImg {
    public $id = 0;
    public $parent_id = 0;
    public $path_img = '';
    public $author_id = 0;
  }

    /*function saveFotoRecipes($arrImg, $name, $parent_id, $author_id, $diet) {
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
    			if ($value->errorText == 0) {
    				mysqli_query($link, "INSERT INTO 	`img_foto_recipes` (`parent_id`, `path_img`, `author_id`) 
    											VALUES 	('$id', '$image_name', '$author_id')");
    			}
    		}
    	}

    	return $id;
    }*/

  function getFotoRecipes($parent_id, $author_id, $page, $diet, $limit) {
    global $link;
    $parent_id = intval($parent_id);
    $author_id = intval($author_id);
    $page = intval($page);
    $diet = intval($diet);
    $limit = intval($limit);

    $str_parent = ($parent_id === 0) ? "" : " `parent_id`='$parent_id' ";
    $str_diet = ($diet === 0) ? "" : " `diet`='$diet' ";
    $str_author = ($author_id === 0) ? "" : " author_id`='$author_id' ";

    if ($str_parent !== '' AND ($str_diet !== '' OR $str_author !== '')) $str_parent .= " AND ";
    if ($author_id !== '' AND $str_diet !== '') $str_author .= " AND ";

    $arrayRecipes = array();

    $result = mysqli_query($link, "SELECT * FROM `foto_recipes` WHERE $str_parent $str_author $str_diet LIMIT $page, $limit");
    while ($recipes = mysqli_fetch_assoc($result)) {
      $data = new DataRecipes();
      $arrayImg = array();

      $data->id = $recipes['id'];
      $data->parent_id = $recipes['parent_id'];
      $data->name = $recipes['name'];
      $data->author_id = $recipes['author_id'];
      $data->diet = $recipes['diet'];

      $resultImg = mysqli_query($link, "SELECT * FROM `img_foto_recipes` WHERE `parent_id`='".$data->id."'");
      while ($img = mysqli_fetch_assoc($resultImg)) {
        $dataImg = new DataImg();
        $dataImg->id = $img['id'];
        $dataImg->parent_id = $img['parent_id'];
        $dataImg->author_id = $img['author_id'];
        $dataImg->path_img = $img['path_img'];

        array_push($arrayImg, $dataImg);
      }

      $data->img = $arrayImg;

      array_push($arrayRecipes, $data);
    }

    return $arrayRecipes;
  }

    /*function deleteFoto($id) {
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

    function getAuthorFoto($id) {
        // Получить ИД автора фото по ИД записи
        global $link;
        $result = mysqli_query($link, "SELECT `author_id` FROM `img_foto_recipes` WHERE `id`='$id' LIMIT 1");
        return intval(mysqli_fetch_row($result)[0]);
    }*/


?>