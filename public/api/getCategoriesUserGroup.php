<?php
	/* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  require 'categoriesRecipe.php';

  header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
  header("Access-Control-Allow-Headers: *");

  if (isset($_GET)) {
    echo json_encode(getCategoriesUserGroup());
  }
?>