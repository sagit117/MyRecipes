<?php
  /* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  require 'fotoRecipes.php';

  header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
  header("Access-Control-Allow-Headers: *");

  if (isset($_POST['id_group'])) {
    $arrName = json_decode($_POST['id_group'], true);
    $arrRecipes = array();

    foreach ($arrName as $value) {
      $arrRecipes = array_merge($arrRecipes, getFotoRecipes($value, 0, 0, 0, 0, 0));
    }

    echo json_encode($arrRecipes);
  }
?>