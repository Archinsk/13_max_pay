<?php // Разлогиниваем пользователя
  // Подключаем RedBeanPHP
  require 'rb.php';
  // Соединямся с БД
  R::setup( 'mysql:host=localhost; dbname=maxscore', 'mikhail', '9039033661!' ); //Указываем адрес сервера, имя базы, логин и пароль пользователя (синтаксис RedBeanPHP)
  // Открываем сессию
  session_start();

  //Удаляем пользователя из сессии
  unset($_SESSION['logged_user']);

  header('Location: maxscore.php');
?>