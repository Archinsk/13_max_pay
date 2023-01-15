<?php //Очистка истории покупок пользователя
  // Подключаем RedBeanPHP и получаем доступ к БД
  require 'db.php';

  //Передаем из POST имя пользователя
  $clearable_user = $_POST['clear_history_user'];

  //Ищем все записи пользователя в таблице истории транзакций
  $purchaseslist = R::find( 'purchases', 'login = :name', [':name' => $clearable_user] );

  //Удаляем все записи из истории пользователя
  R::trashAll( $purchaseslist );

  header('Location: maxscore_adm.php');
?>