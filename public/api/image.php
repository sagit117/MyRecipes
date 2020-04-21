<?php
	/* MyRecipes */
  /* version: 0.0.2 */
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  require_once 'user.php'; // для генерации имени

  class Response {
    public $image_name = '';
    public $errorText = '';
  }

  function saveImage($files) {
    // для сохранения из массива $_POST
    $uploads_dir = 'uploads/';
    $arr = array();

    foreach ($files as $value) {
      $res = new Response();
      $img = str_replace('data:image/png;base64,', '', $value);
      $img = str_replace(' ', '+', $img);
      $fileData = base64_decode($img);
      //saving
      $fileName = $uploads_dir . generateCode(25) . '.png';
      if (file_put_contents('../' . $fileName, $fileData) === false) {
        $res->image_name = $fileName;
        $res->errorText = 'Ошибка при записи данных в файл';
      } else {
        $res->image_name = $fileName;
        $res->errorText = '0';
      }

      array_push($arr, $res);
    }
    return $arr;
  }

  function saveImageFiles($file) { // #не используется
    // для сохранения из массива $_FILES
    $uploads_dir = 'uploads/';
    $arr = array();

    foreach ($file['fileFoto']['tmp_name'] as $k=>$value) {
    	$res = new Response();
    		
    	if (!$file['fileFoto']['error'][$k]) { // проверка на ошибки php
        	if (is_uploaded_file($file['fileFoto']['tmp_name'][$k])) { // Проверка загрузки файла во временное хранилище
        		if (can_upload($file['fileFoto']['name'][$k])) {
        			//Перемещение временного файла
        			$name = $uploads_dir . generateCode(10) . $file['fileFoto']['name'][$k];
            	if (move_uploaded_file($file['fileFoto']['tmp_name'][$k], "../" . $name)) {
                $res->image_name = $name;
                $res->errorText = '0';
            	}
            } else {
            	$res->image_name = $file['fileFoto']['tmp_name'][$k];
        			$res->errorText = "Расширение файла не соответствует заданным.";
            }
        	} else {
        		$res->image_name = $file['fileFoto']['tmp_name'][$k];
        		$res->errorText = "Файл не был передан через POST.";
        	}
    	} else {
    		$res->image_name = $file['fileFoto']['tmp_name'][$k];
    		$res->errorText = "PHP error code: " . $file['fileFoto']['error'][$k];
    	}
    	array_push($arr, $res);
    }
    return $arr;
  }

  // Получаем расширение файла
  function getMime($name) {
    // разбиваем имя файла по точке и получаем массив
    $getMime = explode('.', $name);
    // нас интересует последний элемент массива - расширение
    $mime = strtolower(end($getMime));
    return $mime;
  }

  function can_upload($name){ 
    // если имя пустое, значит файл не выбран
    if($name === '') return false;
    // объявим массив допустимых расширений
    $types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');
		// если расширение не входит в список допустимых - return
    if(!in_array(getMime($name), $types)) return false;
    return true;
  }

  function deleteImg($path_img) {
    if (can_upload($path_img)) {
      return unlink("../" . $path_img);
    } else {
      return false;
    }
  }

?>