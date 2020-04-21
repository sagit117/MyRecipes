<?php
	/* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
	header("Access-Control-Allow-Headers: *");

	require 'sendmail.php';
  require 'user.php';

	class Response {
		public $errorCode = 0;
		public $errorText = '';
		public $code = '';
		public $post = '';
	}

	$res = new Response;
	$res->post = $_GET['user'];
	$res->code = generateCode(6);
  $usr = getUser("post", $res->post);

	if (!filter_var($res->post, FILTER_VALIDATE_EMAIL) or $res->post == '') {
      $res->errorText = "E-mail адрес указан не верно!";
      $res->errorCode = 1;
  }
  
  if ($usr[0]->id > 0) {
      $res->errorText = "E-mail существует!";
      $res->errorCode = 2;
  }

  if ($res->errorCode == 0) {
    smtpmail($res->post, $res->post, 'Ваш код для подтверждения регистрации', 
      'Ваш код для подтверждения регистрации: '.$res->code);
  }

  echo json_encode($res);
?>