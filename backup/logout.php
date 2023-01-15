<?php
// Страница авторизации

// Подключаем RedBeanPHP
require 'rb.php';

// Соединямся с БД
R::setup( 'mysql:host=localhost; dbname=maxscore', 'mikhail', '9039033661!' ); //Указываем адрес сервера, имя базы, логин и пароль пользователя (синтаксис RedBeanPHP)

session_start();

unset($_SESSION['logged_user']);
header('Location: maxscore.php');

?>