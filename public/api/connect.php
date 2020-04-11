<?php
    /* MyRecipes */
    /* version: 0.0.2 */
    /* author: Aksenov Pavel */
    /* 04.2020 */
    /* sagit117@gmail.com */

    //Устанавливаем доступы к базе данных:
    $host = 'localhost'; //имя хоста, на локальном компьютере это localhost
    $user_root = 'u0792747_axel'; //имя пользователя, по умолчанию это root
    $password_root = 'sykqi5-voQpet-qyzzek'; //пароль, по умолчанию пустой
    $db_name = 'u0792747_axel_db'; //имя базы данных
    //Соединяемся с базой данных используя наши доступы:
    $link = mysqli_connect($host, $user_root, $password_root, $db_name);
    
    mysqli_set_charset($link, 'utf8');
    mysqli_query($link, "set names 'utf8'");
    mysqli_query($link, "SET SESSION collation_connection = 'utf8_general_ci'");
?>