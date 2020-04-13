<?php
	/* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  require 'connect.php';

  class Categories {
    public $id = 0;
    public $parent_id = 0;
    public $name = '';
    public $author_id = 0;
  }

  function getCategories($parent_id, $author_id) { // получить категории
    global $link;
    $parent_id = intval($parent_id);
    $author_id = intval($author_id);
    $arr = array();

    if ($author_id > 0) $strAuthor = "AND `author_id`='$author_id'";
    
    $result = mysqli_query($link, "SELECT * FROM `categories_recipes` WHERE `parent_id`='$parent_id' $strAuthor");
    while ($cat = mysqli_fetch_assoc($result)) {
    	$categori = new Categories();
    	$categori->id = $cat['id'];
    	$categori->parent_id = $cat['parent_id'];
    	$categori->name = $cat['name'];
      $categori->author_id = $cat['author_id'];

    	array_push($arr, $categori);
    }

    return $arr;
  }

  function addNewCategory($parent_id, $name, $author_id) { // создать категорию
    global $link;
    $name = mysqli_real_escape_string($link, $name);
    $parent_id = intval($parent_id);
    $author_id = intval($author_id);

    mysqli_query($link, "INSERT INTO `categories_recipes`(`parent_id`, `name`, `author_id`) 
                              VALUES ('$parent_id', '$name', '$author_id')") or die(0);
    return mysqli_insert_id($link);
  }

?>