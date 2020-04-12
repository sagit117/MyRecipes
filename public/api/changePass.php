<?php
	/* MyRecipes */
  /* version: 0.1 */
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
		public $post = '';
	}

	$res = new Response;

  if (isset($_GET['user'])) {
    // смена пароля по get (запрос через форму забыли пароль)
    $res->post = $_GET['user'];
    $usr = getUser("post", $res->post);

    if (intval($usr[0]->id) === 0) {
      $res->errorText = "E-mail не существует!";
      $res->errorCode = 1;
    }

    if (!filter_var($res->post, FILTER_VALIDATE_EMAIL) or $res->post === '') {
      $res->errorText = "E-mail адрес указан не верно!";
      $res->errorCode = 2;
    }

    if ($res->errorCode == 0) {
      $newPass = generateCode(8);
      $psw = md5(md5($newPass));
      if (updateUser("password", $psw, "id", $usr[0]->id)) {
        smtpmail($res->post, $res->post, 'Ваш новый пароль', 'Ваш новый пароль для входа: '.$newPass);
      } else {
        $res->errorText = "Не удалось изменить пароль!";
        $res->errorCode = 3;
      }	
    }

    echo json_encode($res);
  }

  if (intval($_POST['user_id']) > 0) {
    // смена пароля из личного кабинета (можно добавить проверку пользователя но вроде излишне)
    $id = intval($_POST['user_id']);
    $oldPass = md5(md5($_POST['oldPass']));
    $newPass = md5(md5($_POST['newPass']));

    $pass = getUser("id", $id)[0]->pass;
    
    if ($oldPass == $pass) {
      if (!updateUser("password", $newPass, "id", $id)) {
        $res->errorCode = 2;
        $res->errorText = "Не удалось изменить пароль!";
      }
      echo json_encode($res);
    } else {
      $res->errorCode = 1;
      $res->errorText = "Старый пароль не совпадает!";

      echo json_encode($res);
    }
  }

?>