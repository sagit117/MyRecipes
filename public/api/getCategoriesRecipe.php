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

	if (isset($_GET['parent_id'])) {
		$parent_id = $_GET['parent_id'];
		$author_id = $_GET['author_id'];

		echo json_encode(getCategories($parent_id, $author_id));
	}
?>