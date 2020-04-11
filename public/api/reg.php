<?php
	/* MyRecipes */
    /* version: 0.0.2 */
    /* author: Aksenov Pavel */
    /* 04.2020 */
    /* sagit117@gmail.com */

    header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
	header("Access-Control-Allow-Headers: *");

    require 'user.php';

    class Response {
    	public $errorCode = 0;
    	public $errorText = '';
    	public $id = 0;
    }

    $res = new Response;
    $login = $_POST['login'];

	if (!filter_var($login, FILTER_VALIDATE_EMAIL) or $login == '') {
        $res->errorText = "E-mail адрес указан не верно!";
        $res->errorCode = 1;
    }

    if ($res->errorCode == 0) {
        $res->id = createUser($login, $_POST['pass']);
        if (intval($res->id) == 0) {
            $res->errorText = "Не удалось зарегистрировать нового пользователя. Ошибка: ".$res->id;
            $res->errorCode = 2;
        }
    }

    echo json_encode($res);
?>