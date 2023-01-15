<?php // Разлогиниваем пользователя
  // Подключаем RedBeanPHP и получаем доступ к БД
  require 'db.php';

  //Удаляем пользователя из сессии
  unset($_SESSION['logged_user']);

  header('Location: maxscore.php');
?>