<?php //Добавление задания в список заданий
  //Подключаем RedBeanPHP
  require 'rb.php';
  R::setup( 'mysql:host=localhost; dbname=maxscore', 'mikhail', '9039033661!' );
  // Открываем сессию
  session_start();

  //Передаем все переменные из POST в переменную data
  $data = $_POST;
  $cost = $data['cost'];
  $name = $_SESSION['logged_user'];
  $purchaseDate = time();

  //Проверяем остаток средств
  $currentuser = R::findOne( 'users', ' login = :log ', [ ':log' => $name ] );
  $currentusercash = $currentuser->cash;

  //Проверка достаточности средств
  if ($currentusercash < $cost) {
    $_SESSION['is_enough'] = false;
    header('Location: maxscore_auth.php');
  } else {
    $_SESSION['is_enough'] = true;
    //Формируем кортеж с данными пользователя
    $purchase = R::dispense('purchases');
      $purchase->login = $name;
      $purchase->cost = $cost;
      $purchase->purchaseDate = $purchaseDate;
    R::store($purchase);
		
    //Обновляем сумму на счету пользователя
    $user = R::findOne( 'users', ' login = :log ', [ ':log' => $name ] );
    $oldcash = $user->cash;
    $user->cash = $oldcash - $cost;
    R::store($user);
	
    header('Location: maxscore_auth.php');
  }
?>