<?php
	/* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

	require_once 'user.php';
	
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
	header("Access-Control-Allow-Headers: *");

	class Response {
		public $errorCode = 0;
		public $errorText = '';
	}

	$res = new Response;
    
	if (isset($_POST['login'])) {
		// логин по post
		$pass = md5(md5($_POST['pass']));
		$user = getUser("post", $_POST['login']);
		
		if (count($user) > 0) {
		    if ($user[0]->pass === $pass) {
		    	updateUserHash($user[0]->id);
		      echo json_encode($user[0]); exit();
		    } else {
		    	$res->errorCode = 1;
		    	$res->errorText = 'Логин или пароль не совпадают!';
		    	echo json_encode($res); exit();
		    }
		} else {
			$res->errorCode = 2;
		    $res->errorText = 'Пользователь не найден!';
		    echo json_encode($res); exit();
		}
	}

	if (intval($_GET['userID']) > 0) { 
		// логин по hash
		$id_user = intval($_COOKIE['id']); 		// получаем куки ИД
		$hash = getSession('hash');						// получаем хэш из сессии
		if ($hash == getHashUser($id_user)) {	// если хэш в сессии найден в БД хэша по ИД пользователя
			$user = getUser("id", $id_user);		// получить данные пользователя по ИД
			echo json_encode($user[0]); exit(); // вернуть данные пользователя
		}
	}

	function verifyUser($userID) {
		// проверить совпадает ли хэш с ИД пользователя
		$hash = getSession('hash');
		$userID = intval($userID);
		return ($hash == getHashUser($userID)) ? true : false;
	}

?>